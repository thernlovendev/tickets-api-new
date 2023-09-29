<?php

namespace App\Http\Requests\BroadwayMusicals;

use Illuminate\Foundation\Http\FormRequest;

class SelectSeatRequest extends FormRequest
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
    public function rules()
    {
        $salesTypes = "F,G";
        return [
            'sales_type' => 'required|in:' . $salesTypes,
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'show_code' => 'required|string',
            'event_date_time' => 'required|string',
        ];
    }

    public static function build($data)
    {
        $soapXml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">';
        $soapXml .= '<soapenv:Header>';
        $soapXml .= '<tem:AuthHeader>';
        $soapXml .= '<tem:username>'.env('BROADWAY_USERNAME').'</tem:username>';
        $soapXml .= '<tem:password>'.env('BROADWAY_PASSWORD').'</tem:password>';
        $soapXml .= '</tem:AuthHeader>';
        $soapXml .= '</soapenv:Header>';
        $soapXml .= '<soapenv:Body>';
        $soapXml .= '<tem:Select>';
        $soapXml .= '<tem:SaleTypesCode>'.$data['sales_type'].'</tem:SaleTypesCode>';
        $soapXml .= '<tem:ProductId>'.$data['product_id'].'</tem:ProductId>';
        $soapXml .= '<tem:OneShowCode>'.$data['show_code'].'</tem:OneShowCode>';
        $soapXml .= '<tem:EventDateTime>'.$data['event_date_time'].'</tem:EventDateTime>';
        $soapXml .= '<tem:Quantity>'.$data['quantity'].'</tem:Quantity>';
        $soapXml .= '</tem:Select>';
        $soapXml .= '</soapenv:Body>';
        $soapXml .= '</soapenv:Envelope>';

        return $soapXml;
    }
}
