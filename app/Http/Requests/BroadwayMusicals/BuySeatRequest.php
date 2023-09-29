<?php

namespace App\Http\Requests\BroadwayMusicals;

use Illuminate\Foundation\Http\FormRequest;

class BuySeatRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'show_code' => 'required|string',
            'event_date_time' => 'required|string',
            'price' => 'required',
            'booking_last_name' => 'required',
            'booking_first_name' => 'required',
            'booking_reference_number' => 'required',
            'booking_notes' => 'required',
            'area' => 'required',
            'low_seat_num' => 'required|integer',
            'high_seat_num' => 'required|integer',
            'row' => 'required|string',
            'section' => 'required|integer',
            'number_of_tickets' => 'required|integer',
            'session' => 'required|string',
            'booking_email_address' => 'required|email',
            'country_code' => 'required|integer',
            'area_code' => 'required|integer',
            'phone_number' => 'required',
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
        $soapXml .= '<tem:Buy>';
        $soapXml .= '<tem:ProductId>'.$data['product_id'].'</tem:ProductId>';
        $soapXml .= '<tem:OneShowCode>'.$data['show_code'].'</tem:OneShowCode>';
        $soapXml .= '<tem:EventDateTime>'.$data['event_date_time'].'</tem:EventDateTime>';
        $soapXml .= '<tem:Quantity>'.$data['quantity'].'</tem:Quantity>';
        $soapXml .= '<tem:Price>'.$data['price'].'</tem:Price>';
        $soapXml .= '<tem:BookingLastName>'.$data['booking_last_name'].'</tem:BookingLastName>';
        $soapXml .= '<tem:BookingFirstName>'.$data['booking_first_name'].'</tem:BookingFirstName>';
        $soapXml .= '<tem:BookingReferenceNumber>'.$data['booking_reference_number'].'</tem:BookingReferenceNumber>';
        $soapXml .= '<tem:BookingNotes>'.$data['booking_notes'].'</tem:BookingNotes>';
        $soapXml .= '<tem:seatingVerificationList>';
        $soapXml .= '<tem:BiSeatingVerification>';
        $soapXml .= '<tem:NumberTickets>'.$data['number_of_tickets'].'</tem:NumberTickets>';
        $soapXml .= '<tem:Area>'.$data['area'].'</tem:Area>';
        $soapXml .= '<tem:LowSeatNum>'.$data['low_seat_num'].'</tem:LowSeatNum>';
        $soapXml .= '<tem:HighSeatNum>'.$data['high_seat_num'].'</tem:HighSeatNum>';
        $soapXml .= '<tem:Row>'.$data['row'].'</tem:Row>';
        $soapXml .= '<tem:Section>'.$data['section'].'</tem:Section>';
        $soapXml .= '</tem:BiSeatingVerification>';
        $soapXml .= '</tem:seatingVerificationList>';
        $soapXml .= '<tem:Session>'.$data['session'].'</tem:Session>';
        $soapXml .= '<tem:BookingEmailAddress>'.$data['booking_email_address'].'</tem:BookingEmailAddress>';
        $soapXml .= '<tem:BookingCellPhoneNumber>';
        $soapXml .= '<tem:CountryCode>'.$data['country_code'].'</tem:CountryCode>';
        $soapXml .= '<tem:AreaCode>'.$data['area_code'].'</tem:AreaCode>';
        $soapXml .= '<tem:Number>'.$data['phone_number'].'</tem:Number>';
        $soapXml .= '<tem:Ext>'.$data['extension'].'</tem:Ext>';
        $soapXml .= '</tem:BookingCellPhoneNumber>';
        $soapXml .= '<tem:BookingAddress>'.$data['booking_address'].'</tem:BookingAddress>';
        $soapXml .= '<tem:BookingCity>'.$data['booking_city'].'</tem:BookingCity>';
        $soapXml .= '<tem:BookingState>'.$data['booking_state'].'</tem:BookingState>';
        $soapXml .= '<tem:BookingZipOrPostalCode>'.$data['booking_zip_or_postal_code'].'</tem:BookingZipOrPostalCode>';
        $soapXml .= '<tem:BookingCountry>'.$data['booking_country'].'</tem:BookingCountry>';
        $soapXml .= '</tem:Buy>';
        $soapXml .= '</soapenv:Body>';
        $soapXml .= '</soapenv:Envelope>';


        return $soapXml;
    }
}
