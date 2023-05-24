<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\StockUsed;
use App\Models\TicketStock;
use App\Models\Ticket;
use Carbon\Carbon;
use App\Http\Requests\DashboardDownloadRequest;
use QrCode;
use PDF;
use DB;

class UsersDashboard extends Controller
{
    public function downloadTicket(DashboardDownloadRequest $request, Reservation $reservation, ReservationSubItem $reservationSubItem){
        try {
            DB::beginTransaction();
            
            $reservationSubItem->load('reservationItem');
            
            $quantity = $reservationSubItem->reservationItem->quantity;
            $range_age = $reservationSubItem->reservationItem->adult_child_type;
            $ticket_id = $reservationSubItem->ticket_id;
            
            $now = Carbon::now()->format('Y-m-d H:i:s');
            
            $stocks = TicketStock::where('status',TicketStock::STATUS['VALID'])
                        ->where('expiration_date','>', $now)
                        ->where('ticket_id', $ticket_id)
                        ->where('range_age_type',$range_age)
                        ->take($quantity)
                        ->get();
            

            if(isset($stocks)){
                $data = [];
                foreach ($stocks as  $key => $stock) {
                    $data[] = StockUsed::create([
                        'date_used' => $now,
                        'reservation_id' => $reservation->id,
                        'ticket_stock_id' => $stock->id,
                        'reservation_sub_item_id' => $reservationSubItem->id
                    ]);
                    
                    $data[$key]['code'] = $stock->code_number;
                    $data[$key]['expiration_date'] = $stock->expiration_date;
                }
                
                $ticket = Ticket::with('galleryImages')->where('id',$reservationSubItem->ticket_id)->first();
                $data['ticket'] = $ticket;
                // $codes = collect(array_column($data, 'code'));

                $pdf = PDF::loadView('ticketDownload',compact('data'));
                
                // Create PDF and Download
                return $pdf->download('tickets'.$now.'.pdf');

                dd('done');
                DB::commit();

                return $data;
                
            } else {
                return 'Empty Stock';
            }
        
        } catch(\Exception $e) {
            DB::rollback();
            return Response($e,400);

        }
    }
    
}
