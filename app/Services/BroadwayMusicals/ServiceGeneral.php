<?php

namespace App\Services\BroadwayMusicals;

use Illuminate\Support\Facades\Log;
use WsdlToPhp\PackageBase\AbstractSoapClientBase;
use \App\Services\BroadwayMusicals\Src\ClassMap;

use \StructType\AuthHeader;

use \ServiceType\City;
use \StructType\CityList;
use \ServiceType\Show;
use \StructType\ShowBasics;
use \ServiceType\Select;
use \StructType\Select as SelectType;
use \ServiceType\Buy;
use \StructType\Buy as BuyType;

use \StructType\MainResponseFormat;
use \StructType\PhoneInfo;
use \StructType\BiSeatingVerification;

use \ArrayType\ArrayOfBiSeatingVerification;


class ServiceGeneral
{
    private $wsdl_url = '';
    private $username = '';
    private $password = '';
    private $options;
    private $header;

    public function __construct()
    {
        $this->wsdl_url = env('BROADWAY_URL');
        $this->username = env('BROADWAY_USERNAME');
        $this->password = env('BROADWAY_PASSWORD');

        $this->options = [
            AbstractSoapClientBase::WSDL_URL => $this->wsdl_url,
            AbstractSoapClientBase::WSDL_CLASSMAP => ClassMap::get()
        ];

        $this->header = new AuthHeader($this->username, $this->password);
    }

    public function selectSeat($data)
    {
        $select = new Select($this->options);
        $select->setSoapHeaderAuthHeader($this->header);

        $sales_type = $data['sales_type'];
        $product_id = $data['product_id'];
        $quantity = $data['quantity'];
        $show_code = $data['show_code'];
        $date = $data['event_date_time'];

        try {
            $select->Select(new SelectType(
                $sales_type, $product_id, $show_code, $quantity, $date
            ));
            $select_response = $select->getResult();
            
            $main_response = new MainResponseFormat();
            $response = $main_response->convertXmlToJson($select_response, 'Select');
            return $response;
        } catch (\Exception $e)
        {
            Log::error('Broadwaymuscials API Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function buySeat($data)
    {
        $buy = new Buy($this->options);
        $buy->setSoapHeaderAuthHeader($this->header);

        $product_id = $data['product_id'];
        $show_code = $data['show_code'];
        isset($data['event_date_time']) ? $date = $data['event_date_time'] : '';
        $quantity = $data['quantity'];
        $price = $data['price'];
        $lastname = $data['booking_last_name'];
        $firstname = $data['booking_first_name'];
        $customer_email = $data['booking_email_address'];
        $seating = new BiSeatingVerification("1", "Rear Mezzanine", "", "", "", "");
        $seating = new ArrayOfBiSeatingVerification(
            [$seating]
        );
        
        $phone_info = new PhoneInfo('123', '45', '6789', '23'); //country code, area code, number, extension

        try {
            $buy->Buy(new BuyType(
                $product_id, $show_code, $quantity, $price, $lastname, $firstname, 'some', 'some notes', $seating, 'ec9-776759847e63+C4879F524C1391B694DD9B3B43E2D430', $customer_email, $phone_info, 'testaddress', 'test', 'test', 'teset', 'country', $date
            ));
            $buy_response = $buy->getResult();
            
            $main_response = new MainResponseFormat();
            $response = $main_response->convertXmlToJson($buy_response, 'Buy');
            return $response;
        } catch (\Exception $e)
        {
            Log::error('Broadwaymuscials API Error: ' . $e->getMessage());
            throw $e;
        }
    }
}