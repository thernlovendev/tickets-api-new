<?php

namespace App\Services\Inventories;
use App\Models\Ticket;
use App\Models\Inventory;
use DB;
use Validator;
use App\Utils\ModelCrud;
use Illuminate\Validation\Rule;

class ServiceCrud
{
	public static function register($data)
	{
		try {
            DB::beginTransaction();

            $inventory = Inventory::create(
                [
                    'register_date' => $data['register_date'],
                    'stock_in' => $data['stock_in'],
                    'stock_out' => $data['stock_out'],
                    'type_code' => $data['type_code'],
                    'age_range' => $data['age_range'],
                    'ticket_id' => $data['ticket_id'],
                ]);
         
            DB::commit();

            return $inventory->load('ticket');

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