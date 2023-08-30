<?php

namespace App\Http\Controllers\API;

use App\Services\Inventories\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\StockTicketRequest;
use App\Http\Requests\StockTicketZipRequest;
use App\Http\Requests\StockCorrectionBalanceRequest;
use App\Imports\TicketStocksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TicketStock;
use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\StockUsed;
use App\Models\Ticket;
use App\Models\StockCorrectionBalance;
use App\Services\Inventories\ServiceGeneral;
use App\Services\Inventories\Details\ServiceGeneral as ServiceDetail;
use App\Services\Inventories\StockCorrectionBalance\ServiceCrud as ServiceStockCorrection;
use PDF;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Zip;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class InventoriesController extends Controller
{

    public function index(Request $request)
    {
        $stock = TicketStock::query();
        $params = $request->query();

        if($stock->count() > 0){
            // $stocks = $stock->join('tickets', 'ticket_stocks.ticket_id', '=', 'tickets.id')->selectRaw('ticket_id, title_en, product_code, range_age_type, tickets.out_of_stock_alert_adult, tickets.out_of_stock_alert_child, count(*) as total, count(CASE WHEN ticket_stocks.status = "Valid" THEN 1 END) AS total_valid, MAX(ticket_stocks.created_at) AS last_update')->groupBy('ticket_id', 'range_age_type');

            $stocks = $stock->join('tickets', 'ticket_stocks.ticket_id', '=', 'tickets.id')->selectRaw('ticket_id, title_en, product_code, range_age_type, tickets.out_of_stock_alert_adult,tickets.out_of_stock_alert_child, count(*) as total, count(CASE WHEN ticket_stocks.status = "Valid" THEN 1 END) AS total_valid, MAX(ticket_stocks.created_at) AS last_update')->groupBy('ticket_id', 'range_age_type','title_en', 'product_code', 'tickets.out_of_stock_alert_adult','tickets.out_of_stock_alert_child');

            $elements = ServiceGeneral::filterCustom($params, $stocks);
            $elements = $this->httpIndex($elements, []);
            $response = ServiceGeneral::mapCollection($elements);
            return Response($response, 200);
        } else {
            return [];
        }

        
    }

    public function stockCorrection(StockCorrectionBalanceRequest $request)
    {
        $data = $request->validated();
        $stock = ServiceStockCorrection::create($data);
        return Response($stock, 200);

    }

    public function bulkUpload(StockTicketRequest $request)
    {
        $data = $request->validated();

        Excel::import(new TicketStocksImport($data), $data['file_import']);
        return Response(['message'=> 'Successful Bulk Up'], 200);
    }

    public function bulkUploadZip(StockTicketZipRequest $request)
    {
        try {
            DB::beginTransaction();
            $zipPath = $request->file('file_import')->store('temp');
            $zip = new \ZipArchive;

            $zip->open(storage_path('app/' . $zipPath));
            $folder = Carbon::now()->format('YmdHis');
            $extractPath = storage_path('app/public/stock_pdfs/'.$folder);

            $zip->extractTo($extractPath);
            $zip->close();
            
            $pdfFiles = Storage::files('public/stock_pdfs/'.$folder);
            $errors = [];
            $message = 'Successful Bulk Up';
            foreach ($pdfFiles as $pdfFile) {
                $pdfContents = Storage::get($pdfFile);
                $pdfPath = Storage::path($pdfFile);
    
                $pdfName = pathinfo($pdfFile, PATHINFO_FILENAME);
                $stock = TicketStock::where('code_number', $pdfName)->first();
                if(!$stock){
                    $ticketStock = TicketStock::create([
                        'code_number' => $pdfName,
                        'type' => $request->input('type'),
                        'expiration_date' => $request->input('expiration_date'),
                        'status' => TicketStock::STATUS['VALID'],
                        'range_age_type' => $request->input('range_age_type'),
                        'ticket_id' => $request->input('ticket_id')
                    ]);
        
                    $ticketStock->pdf()->create([
                        'name' => $pdfName,
                        'path' => $pdfPath
                    ]);
                } else {
                    $errors[] = [
                        'code_number' => $pdfName
                    ];
                    $message = 'Some codes are repeated, only the correct ones have been loaded';
                }
            }

            if (Storage::exists($zipPath)) {
                Storage::delete($zipPath);
            }
            
            DB::commit();
    
            return response(['message'=> $message, 'errors' => $errors], 200);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response($e, 400);
        }
    }

    public function details(Request $request, $ticket_id, $type){

        $stock = TicketStock::query();
        $params = $request->query();

        $stocks = $stock->where('ticket_id',$ticket_id)->
                          where('range_age_type', $type);

        $elements = ServiceDetail::filterCustom($params, $stocks);
        $elements = $this->httpIndex($elements, []);
        $response = ServiceDetail::mapCollection($elements);
        return Response($response, 200);
        
    }

    public function downloadTickets(Reservation $reservation, ReservationSubItem $reservationSubItem){
        $result_file_name = 'tickets_all_' . time() . '.pdf';

        if($reservationSubItem->pdf_path){
            return response()->download($reservationSubItem->pdf_path, $result_file_name)->deleteFileAfterSend(false);
        } else {
            return response(['message'=> 'The PDF is not available'], 400);
        }
    }

    public function stockBalance(Request $request){
        
        $params = $request->query();
        //filters received
        if($request->filled('start_date') && $request->filled('end_date')){
            $start_date = Carbon::parse($params['start_date'])->format('Y-m-d');
            $end_date = Carbon::parse($params['end_date'])->format('Y-m-d');
        }else{
            $end_date = Carbon::now()->format('Y-m-d');
            $start_date = Carbon::now()->subDays(7)->format('Y-m-d');
        }
        $balance_type = 'All';
        if($request->filled('balance_type')){
            $balance_type = $request->input('balance_type');
        }

        //create period of calendar for show all dates
        $dates = CarbonPeriod::create($start_date, $end_date);
        $calendar = [];
        foreach ($dates as $date) {
           $calendar[] = $date->toDateString();
        }

        //Stock
        $tickets = TicketStock::leftJoin('tickets', 'ticket_stocks.ticket_id', '=', 'tickets.id')
            ->leftJoin('stock_used', 'ticket_stocks.id', '=', 'stock_used.ticket_stock_id')
            ->leftJoin('stock_correction_balance', 'ticket_stocks.ticket_id', '=', 'stock_correction_balance.ticket_id')
            ->select(
                'tickets.id',
                'tickets.title_en',
                'ticket_stocks.range_age_type',
            )->where(function ($query) use ($start_date, $end_date) {
            $query->whereHas('stocksUsed', function ($subquery) use ($start_date, $end_date) {
                $subquery->whereBetween('date_used', [$start_date, $end_date]);
            })->orWhereBetween('ticket_stocks.created_at', [$start_date, $end_date])->orWhereBetween('stock_correction_balance.register_date',[$start_date, $end_date]);
        })->groupBy('ticket_stocks.range_age_type', 'tickets.id','tickets.title_en')
        ->orderBy('tickets.id')
        ->get()
        ->map(function($item) use($calendar){
            $stock_in = TicketStock::where('ticket_id', $item->id)->where('range_age_type', $item->range_age_type)->count();
            $stock_out = StockUsed::whereHas('ticketStock', function($query) use($item) {
                $query->whereHas('ticket', function($q) use($item){
                    $q->where('id', $item->id);
                })->where('range_age_type', $item->range_age_type);
            })->count();

            $stock_in_correction = StockCorrectionBalance::where('ticket_id',$item->id)->where('range_age_type',$item->range_age_type)->pluck('stock_in')->sum();
            $stock_out_correction = StockCorrectionBalance::where('ticket_id',$item->id)->where('range_age_type',$item->range_age_type)->pluck('stock_out')->sum();

            $dates = [];
            foreach($calendar as $day){

                $stock_in_day = TicketStock::where('ticket_id', $item->id)
                        ->where('range_age_type', $item->range_age_type)
                        ->whereDate('created_at', $day)->count();
                $stock_out_day = StockUsed::whereHas('ticketStock', function($query) use($item) {
                    $query->whereHas('ticket', function($q) use($item){
                        $q->where('id', $item->id);
                    })->where('range_age_type', $item->range_age_type);
                })->whereDate('date_used', $day)->count();
                
                $stock_in_correction_balance = StockCorrectionBalance::whereDate('register_date',$day)->where('ticket_id',$item->id)->where('range_age_type',$item->range_age_type)->pluck('stock_in')->sum();
                $stock_out_correction_balance = StockCorrectionBalance::where('register_date',$day)->where('ticket_id',$item->id)->where('range_age_type',$item->range_age_type)->pluck('stock_out')->sum();

                $stock_in_final = $stock_in_day + $stock_in_correction_balance;
                $stock_out_final = $stock_out_day + $stock_out_correction_balance;

                $dates[] = [
                    'date' => $day,
                    'in' => $stock_in_final,
                    'out' => $stock_out_final
                ];
            }

            return [
                'ticket_id' => $item->id,
                'title_en' => $item->title_en,
                'range_age_type' => $item->range_age_type,
                'balance_general' => ($stock_in + $stock_in_correction) - ($stock_out + $stock_out_correction),
                'dates' => $dates
            ]; 
        })->filter(function($item) use($balance_type) {
            if($balance_type == 'Positive'){
                return strval($item['balance_general']) > 0;
            } else if($balance_type == 'Negative'){
                return strval($item['balance_general']) < 0;
            } else return true;
        });

        return $tickets;
    }

    public function changeStatus(TicketStock $stock){

        if($stock->status == $stock->status = TicketStock::STATUS['VALID'] ){
            $stock->status = TicketStock::STATUS['USED'];
            $stock->save();
        } else {
            $stock->status = TicketStock::STATUS['VALID'];
            $stock->save();
        }
        return Response($stock, 200);

    }

    
}
