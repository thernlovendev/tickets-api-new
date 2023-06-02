<?php

namespace App\Http\Controllers\API;

use App\Services\Inventories\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\StockTicketRequest;
use App\Imports\TicketStocksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TicketStock;
use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\StockUsed;
use App\Models\Ticket;
use App\Services\Inventories\ServiceGeneral;
use App\Services\Inventories\Details\ServiceGeneral as ServiceDetail;
use PDF;
use DB;
use Carbon\Carbon;

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

    public function register(InventoryRequest $request)
    {
        $inventory = ServiceCrud::register($request);
        return $inventory;
    }

    public function bulkUpload(StockTicketRequest $request)
    {
        $data = $request->validated();

        Excel::import(new TicketStocksImport($data), $data['file_import']);
        return Response(['message'=> 'Successful Bulk Up'], 200);
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
        try {
            DB::beginTransaction();
            
            $reservationSubItem->load('reservationItem');
            
            $quantity = $reservationSubItem->reservationItem->quantity;
            $range_age = $reservationSubItem->reservationItem->adult_child_type;
            $ticket_id = $reservationSubItem->ticket_id;
            
            $now = Carbon::now()->format('Y-m-d H:i:s');

            $stocks = StockUsed::where('reservation_id',$reservation->id)
                               ->where('reservation_sub_item_id',$reservationSubItem->id)
                               ->get();
            $data = [];

            foreach ($stocks as  $key => $stock) {
                $ticket_stock = TicketStock::find($stock->ticket_stock_id);
                $data[] = $stock;
                
                $data[$key]['code'] = $ticket_stock->code_number;
                $data[$key]['expiration_date'] = $ticket_stock->expiration_date;
                $data[$key]['type'] = $ticket_stock->type;
            }
            
            $ticket = Ticket::where('id',$reservationSubItem->ticket_id)->first();
            $gallery = $ticket->galleryImages->sortBy('priority')->first();
            $image_logo = public_path('logo.png');
            
            if($gallery == null){
                $image = $image_logo;
            } else {
                $image = storage_path().'/app/public/'.$gallery->path;
            }
            $pdf = PDF::loadView('ticketDownload',compact('data','ticket','image','reservation','image_logo'));
            
            // Create PDF and Download
            return $pdf->download('tickets'.$now.'.pdf');

            dd('done');
            DB::commit();

            return $data;
    
        } catch(\Exception $e) {
            DB::rollback();
            return Response($e,400);

        }
    }
}
