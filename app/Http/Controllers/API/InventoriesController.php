<?php

namespace App\Http\Controllers\API;

use App\Services\Inventories\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\StockTicketRequest;
use App\Http\Requests\StockTicketZipRequest;
use App\Http\Requests\StockCorrectionBalanceRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\DownloadMultipleTicketsRequest;
use App\Http\Requests\DeleteMultipleTicketDetail;
use App\Http\Requests\DestroyMultipleUploadedRequest;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Zip;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class InventoriesController extends Controller
{

    public function index(Request $request)
    {
        $stock = TicketStock::whereHas('ticket', function($query){ 
            $query->where('deleted_at', null);
        });
        $params = $request->query();

        if($stock->count() > 0){

            $stocks = $stock->join('tickets', 'ticket_stocks.ticket_id', '=', 'tickets.id')->selectRaw('ticket_id, title_en, product_code, range_age_type, tickets.out_of_stock_alert_adult,tickets.out_of_stock_alert_child, count(*) as total, SUM(CASE 
            WHEN ticket_stocks.status = "Valid" AND ticket_stocks.expiration_date >= NOW() THEN 1 ELSE 0 END) AS total_valid, MAX(ticket_stocks.created_at) AS last_update')->groupBy('ticket_id', 'range_age_type','title_en', 'product_code', 'tickets.out_of_stock_alert_adult','tickets.out_of_stock_alert_child');

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

    public function downloadPdfZip(Request $request){

        if($request->filled('ticket_stock_id')){
            $ticket_stock_id = $request->input('ticket_stock_id');
            $ticket_stock = TicketStock::find($ticket_stock_id);
    
            if($ticket_stock && $ticket_stock->pdf){
                $path = $ticket_stock->pdf->path;
                $result_file_name = $ticket_stock->pdf->name.'.pdf';
                return response()->download($path, $result_file_name)->deleteFileAfterSend(false);
            }
        }

        return response(['message' => 'The pdf is not available'], 400);

    }

    public function downloadTickets(Reservation $reservation, ReservationSubItem $reservationSubItem){
        $result_file_name = 'tickets_all_' . time() . '.pdf';

        if($reservationSubItem->pdf_path){
            return response()->download($reservationSubItem->pdf_path, $result_file_name)->deleteFileAfterSend(false);
        } else {
            return response(['message'=> 'The PDF is not available'], 400);
        }
    }

    public function downloadMultipleTickets(DownloadMultipleTicketsRequest $request){

        //validator custom
        $validator = Validator::make($request->all(), [
            'ticket_stock_ids' => function ($attribute, $value, $fail) use ($request) {
                $firstType = TicketStock::find($value[0])->type;
    
                if (!collect($value)->every(function ($ticketStockId) use ($firstType) {
                    $type = TicketStock::find($ticketStockId)->type;
                    return $type === $firstType;
                })) {
                    $fail('Tickets must be of the same type.');
                }
            },
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $ticket_stock_ids = $request->input('ticket_stock_ids');
        $quantity = collect($ticket_stock_ids)->count();

        $ticket_stocks = TicketStock::whereIn('id', $ticket_stock_ids)->with('pdf')->get();
        
        if($quantity <= env('QUANTITY_TO_DOWNLOAD_PDF')){
            //download pdf in one merge
            $oMerger = PDFMerger::init();

            foreach ($ticket_stocks as $ticket_stock) {
                $oMerger->addPDF($ticket_stock->pdf->path, 'all');
            }

            $oMerger->merge();
            $result_file_name = 'Ticket_stocks_' . time() . '.pdf';
            $save_path = storage_path('app/public/' . $result_file_name);
            $oMerger->setFileName($result_file_name)->save($save_path);

            return response()->download($save_path, $result_file_name)->deleteFileAfterSend(true);

        } else {
            //download pdf individual in a zip file
            Storage::disk('local')->makeDirectory('tobedownload',$mode=0775); // zip store here
            $zip_file=storage_path('app/tobedownload/tickets_'.time().'.zip');
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            $pdf_paths = $ticket_stocks->pluck('pdf.path');
        
            foreach ($pdf_paths as $pdf_path) {
                if (file_exists($pdf_path)) { // verify if file exists
                    $file_path = $pdf_path;
                    $relative_path = basename($pdf_path); // name relative route
                    $zip->addFile($file_path, $relative_path);
                }
            }
            $zip->close();
            $zip_new_name = "Tickets-".date("y-m-d-h-i-s").".zip";
            return response()->download($zip_file,$zip_new_name)->deleteFileAfterSend(true);
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
        })->values();


        $reservation_sub_items = ReservationSubItem::join('tickets', 'reservation_sub_items.ticket_id', '=', 'tickets.id')->join('reservation_items', 'reservation_sub_items.reservation_item_id', '=', 'reservation_items.id')->where('ticket_type', '!=',Ticket::TYPE['BAR_QR'])->where(function ($query) use ($start_date, $end_date) {
            $query->orWhereHas('optionsSchedules', function ($subquery) use ($start_date, $end_date){
                $subquery->whereBetween('created_at', [$start_date, $end_date]);
            })->orWhereBetween('reservation_sub_items.ticket_sent_date',[$start_date, $end_date]);
        })
        ->select(
            'ticket_id',
            'title_en',
            'adult_child_type'
        )
        ->groupBy('title_en','adult_child_type','ticket_id')->get()
        ->map(function($item) use($calendar){
    
                $dates = [];
                foreach($calendar as $day){

                    $count_day =  ReservationSubItem::where('ticket_id',$item->ticket_id)->join('tickets', 'reservation_sub_items.ticket_id', '=', 'tickets.id')->join('reservation_items', 'reservation_sub_items.reservation_item_id', '=', 'reservation_items.id')->where('adult_child_type',$item->adult_child_type)->where('ticket_type', '!=',Ticket::TYPE['BAR_QR'])->where(function ($query) use ($day) {
                        $query->orWhereHas('optionsSchedules', function ($subquery) use ($day){
                            $subquery->whereDate('created_at', $day);
                        })->orWhereDate('reservation_sub_items.ticket_sent_date',$day);
                    })->select(
                        'ticket_id',
                        'title_en',
                        'adult_child_type',
                        DB::raw('SUM(reservation_items.quantity) * COUNT(*) as stock_day'),
                    )->groupBy('ticket_id', 'title_en', 'adult_child_type')
                    ->get();
                
                    $stock_day_total = $count_day->sum('stock_day');;

                    
                    $dates[] = [
                        'date' => $day,
                        'in' => null,
                        'out' => $stock_day_total
                    ];
                }
    
                return [
                    'ticket_id' => $item->ticket_id,
                    'title_en' => $item->title_en,
                    'range_age_type' => $item->adult_child_type,
                    'balance_general' => collect($dates)->sum('out'),
                    'dates' => $dates
                ]; 
            });

            $result = $reservation_sub_items->merge($tickets);

        return $result->sortBy('ticket_id')->values();
    }

    public function changeStatus(ChangeStatusRequest $request,TicketStock $stock){

        $new_type = $request->validated();
        

        $stock->update(['status' => $new_type['status']]);

        return Response($stock, 200);

    }

    public function deleteMultipleTicketStockDetail(DeleteMultipleTicketDetail $request){

        $validator = Validator::make($request->all(), [
            'ticket_stock_ids' => function ($attribute, $value, $fail) use ($request) {
                    $invalidIds = TicketStock::whereIn('id', $value)
                        ->where('status', 'Used')
                        ->pluck('id');
    
                    if ($invalidIds->count() > 0) {

                        $fail('It is only allowed to delete if the tickets are not used');
                    }
                },
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }       
    
        $ticket_stock_ids = $request->input('ticket_stock_ids');

        TicketStock::whereIn('id',$ticket_stock_ids)->forceDelete();

        return response(['message' => 'Tickets delete successfully'], 204);
    }

    public function destroyMultipleUploaded($ticket_id, DestroyMultipleUploadedRequest $request){

        $data = $request->validated();
        $stocks = TicketStock::where('ticket_id', $ticket_id)
            ->where('range_age_type', $data['range_age_type'])
            ->forceDelete();

        if ($stocks > 0) {
            // Se eliminaron registros, devolver una respuesta exitosa con un mensaje
            return response()->json(['message' => 'Records successfully deleted'], 200);
        } else {
            // No se eliminaron registros, devolver un mensaje de error
            return response()->json(['message' => 'No records found to delete'], 404);
        }
    }

    
}
