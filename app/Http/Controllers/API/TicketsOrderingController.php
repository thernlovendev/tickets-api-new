<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TicketOrderingRequest;
use App\Models\Ticket;
use DB;

class TicketsOrderingController extends Controller
{
    public function updateOrdering(TicketOrderingRequest $request){

        try{
            DB::beginTransaction();

            $data = $request->validated();
    
            foreach ($data as $value) {
                $ticket = Ticket::find($value['ticket_id'])->update(['order' => $value['order']]);
            }

            DB::commit();

            return Response(['message' => 'Save successfully'], 200);

        } catch( \Exception $e){
            DB::rollback();

            return Response($e, 400);
        }
    }
}
