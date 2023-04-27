<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketSchedule;
use DB;
use Carbon\Carbon;
use App\Utils\ModelCrud;
use App\Http\Requests\TicketScheduleRequest;

class TicketSchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TicketScheduleRequest $request, Ticket $ticket, TicketSchedule $ticketSchedule)
    {
        
        try{
            $data = $request->validated();
            
            DB::beginTransaction();
            
            $data['week_days'] = collect($data['week_days'])->toJson();
            $ticketSchedule->update($data);

            if(isset($data['ticket_schedule_exceptions'])){
                $exceptions = [];
                foreach ($data['ticket_schedule_exceptions'] as $key => $exception) {
                    $exceptions[$key]['id'] = isset($exception['id']) ? $exception['id'] : null;
                    $exceptions[$key]['time'] = $ticketSchedule->time;
                    $exceptions[$key]['date'] = $exception['date'];
                    $exceptions[$key]['max_people'] = $exception['max_people'];
                    $exceptions[$key]['show_on_calendar'] = $exception['show_on_calendar'];
                    $exceptions[$key]['day'] = Carbon::parse($exception['date'])->format('l');
                }
    
                ModelCrud::deleteUpdateOrCreate($ticketSchedule->ticketScheduleExceptions(), $exceptions);
            }

            DB::commit();
            
            return Response($ticketSchedule->load('ticketScheduleExceptions'), 200);
        }catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Ticket $ticket, TicketSchedule $ticketSchedule)
    {
        
        $ticketSchedule->delete();

        return Response(['message'=> 'Delete Schedule Successfully'], 204);
    }
}
