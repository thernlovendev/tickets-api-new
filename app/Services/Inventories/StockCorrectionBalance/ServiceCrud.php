<?php

namespace App\Services\Inventories\StockCorrectionBalance;
use App\Models\StockCorrectionBalance;
use Illuminate\Support\Facades\Auth;
use DB;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $created_by = Auth::user()->name;

            $stock = StockCorrectionBalance::create(
                [
                    'register_date' => $data['register_date'],
                    'stock_in' => $data['stock_in'],
                    'stock_out' => $data['stock_out'],
                    'type' => $data['type'],
                    'range_age_type' => $data['range_age_type'],
                    'created_by' => $created_by,
                    'ticket_id' => $data['ticket_id'],
                ]);
         
            DB::commit();

            return $stock->load('ticket');

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

    public static function response($ticket)
    {
        return $ticket;
    }
}