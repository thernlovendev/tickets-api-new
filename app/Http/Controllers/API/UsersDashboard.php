<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\StockUsed;
use App\Models\TicketStock;
use App\Models\Ticket;
use App\Models\Template;
use Carbon\Carbon;
use App\Http\Requests\DashboardDownloadRequest;
use PDF;
use DB;
use Mail;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class UsersDashboard extends Controller
{
    // public function downloadTicket(DashboardDownloadRequest $request, Reservation $reservation, ReservationSubItem $reservationSubItem){
    //     try {
    //         DB::beginTransaction();
            
    //         $reservationSubItem->load('reservationItem');
            
    //         $quantity = $reservationSubItem->reservationItem->quantity;
    //         $range_age = $reservationSubItem->reservationItem->adult_child_type;
    //         $ticket_id = $reservationSubItem->ticket_id;
            
    //         $now = Carbon::now()->format('Y-m-d H:i:s');
            
    //         $stocks = TicketStock::where('status',TicketStock::STATUS['VALID'])
    //                     ->where('expiration_date','>', $now)
    //                     ->where('ticket_id', $ticket_id)
    //                     ->where('range_age_type',$range_age)
    //                     ->take($quantity)
    //                     ->get();
            
    //         if(isset($stocks)){
    //             $data = [];
    //             foreach ($stocks as  $key => $stock) {
    //                 $data[] = StockUsed::create([
    //                     'date_used' => $now,
    //                     'reservation_id' => $reservation->id,
    //                     'ticket_stock_id' => $stock->id,
    //                     'reservation_sub_item_id' => $reservationSubItem->id
    //                 ]);
                    
    //                 $stock->update([
    //                     'status' => TicketStock::STATUS['USED']
    //                 ]);
                    
    //                 $data[$key]['code'] = $stock->code_number;
    //                 $data[$key]['expiration_date'] = $stock->expiration_date;
    //                 $data[$key]['type'] = $stock->type;
    //             }
                
    //             $ticket = Ticket::where('id',$reservationSubItem->ticket_id)->first();
    //             $gallery = $ticket->galleryImages->sortBy('priority')->first();
    //             $image_logo = public_path('logo.png');
                
    //             if($gallery == null){
    //                 $image = $image_logo;
    //             } else {
    //                 $image = storage_path().'/app/public/'.$gallery->path;
    //             }

    //             $pdf = PDF::loadView('ticketDownload',compact('data','ticket','image','reservation','image_logo'));
                
    //             DB::commit();
                
    //             return $pdf->download('tickets'.$now.'.pdf');
                
    //         } else {
    //             return 'Empty Stock';
    //         }
        
    //     } catch(\Exception $e) {
    //         DB::rollback();
    //         return Response($e,400);

    //     }
    // }

    public function emailDownloadTicket(DashboardDownloadRequest $request, Reservation $reservation, ReservationSubItem $reservationSubItem){
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

                $oMerger = PDFMerger::init();

                $data = [];
                foreach ($stocks as  $key => $stock) {
                    $stock_used = StockUsed::create([
                        'date_used' => $now,
                        'reservation_id' => $reservation->id,
                        'ticket_stock_id' => $stock->id,
                        'reservation_sub_item_id' => $reservationSubItem->id
                    ]);
                    
                    $stock->update([
                        'status' => TicketStock::STATUS['USED']
                    ]);

                    if($stock->type == TicketStock::TYPE['ZIP']){
                        //ubica el pdf guardado para el ticketStock
                       $oMerger->addPDF($stock->pdf->path, 'all');
                    } else {
                        $data[$key]['code'] = $stock->code_number;
                        $data[$key]['expiration_date'] = $stock->expiration_date;
                        $data[$key]['type'] = $stock->type;
                    }
                }
                
                $ticket = Ticket::where('id',$reservationSubItem->ticket_id)->first();

                $image_template = Template::find($ticket->template_id);
                
                $image = storage_path().'/app/public/'.$image_template->image->path;

                //if data is not empty, create a pdf with tickets (autogenerated).
                if(isset($data)){
                    $data = json_decode(json_encode($data), false);
                    $pdf = PDF::loadView('ticketDownload',compact('data','ticket','image','reservation'));
                    
                    //save pdf
                    $pdf_content = $pdf->output();
                   
                    $temp_file_path = storage_path('app/public/'.time().'.pdf');
                    File::put($temp_file_path, $pdf_content);
                    $oMerger->addPDF($temp_file_path, 'all');
                }

                
                $template = Template::where('title','After Tickets Uploaded By Admin')->first();
        

                if($template->subject == 'default'){
                    $subject = "Tickets of reservation";
                } else {
                    $subject = $template->subject;
                }
                
                // Mail::send('email.sendTicketEmail', ['fullname' => $reservation->customer_name_en, 'template' => $template], function($message) use($reservation, $template, $subject, $pdf){
                //     $message->to($reservation->email);
                //     $message->subject($subject);
                //     $message->attachData($pdf->output(), 'tickets.pdf');
                // });

                $oMerger->merge();
                $result_file_name = 'tickets_all_' . time() . '.pdf';

                // Save Pdf result
                $save_path = storage_path('app/public/' . $result_file_name);
                $oMerger->setFileName($result_file_name)->save($save_path);

                $reservationSubItem->update(['pdf_path' => $save_path]);
                
                DB::commit();

                //download pdf result
                return response()->download($save_path, $result_file_name)->deleteFileAfterSend(false);
                
            } else {
                return 'Empty Stock';
            }
        
        } catch(\Exception $e) {
            DB::rollback();
            return Response($e,400);

        }
    }
    
    
}
