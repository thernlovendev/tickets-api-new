<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\TicketStock;
use App\Models\StockUsed;
use Carbon\Carbon;
class DashboardDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public $download, $quantity_stock;
    
    public function rules()
    {
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    $quantity = $this->quantity;
                    $reservation = $this->route('reservation');
                    $reservation_sub_item = $this->route('reservationSubItem');

                    $quantity = $reservation_sub_item->reservationItem->quantity;
                    $range_age = $reservation_sub_item->reservationItem->adult_child_type;
                    $ticket_id = $reservation_sub_item->ticket_id;
                    
                    $now = Carbon::now()->format('Y-m-d H:i:s');
                    
                    $stocks = TicketStock::where('status',TicketStock::STATUS['VALID'])
                                ->where('expiration_date','>', $now)
                                ->where('ticket_id', $ticket_id)
                                ->where('range_age_type',$range_age)
                                ->take($quantity)
                                ->pluck('id');
                                
                    $ticket_taked = StockUsed::where('reservation_id',$reservation['id'])->where('reservation_sub_item_id',$reservation_sub_item['id'])->get()->count();

                    if ($ticket_taked === 0) {
                        
                        if(count($stocks) < $quantity){
                            return [
                                'quantity_stock' => 'required'
                            ];
                        }
                        return [
                            'download' => 'nullable'
                        ];
                    } else {
                        
                        return [
                            'download' => 'required'
                        ];
                    }
                    
                } break;

            case 'PUT':{
                return [];
            } break;

            case 'DELETE': break;
            default:
            {
                return [];
            } break;
        }

    }
    public function messages()
    {
        return [
            'download.required' => 'The PDF is already Downloaded',
            'quantity.required' => 'Quantity exceeds available stock',
        ];
    }
    
}
