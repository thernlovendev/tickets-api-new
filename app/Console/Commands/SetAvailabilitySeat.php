<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductSeat;
use App\Services\BroadwayMusicals\ServiceGeneral;
use Carbon\Carbon;

class SetAvailabilitySeat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:AvailabilitySeat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to set in product seat Availability';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = ProductSeat::get();
        $result = [];
        $to_delete = [];
        foreach ($products as $key => $product) {
            echo $key.PHP_EOL;
            if(isset($product->product_date) && ($product->product_time)){
                
                $date = $product->product_date;
                echo $date.PHP_EOL;

                $datetime_begin = Carbon::createFromFormat('m/d/y', $date);
                $datetime_end = $datetime_begin->clone()->addWeeks(1);
                $event_date_begin = $datetime_begin->format('Y-m-d');
                $event_date_end = $datetime_end->format('Y-m-d');
    
                $data = [
                    'sales_type' => 'F',
                    'show_code' => $product->product_code,
                    'show_city_code' => 'NYCA',
                    'event_date_end' => $event_date_end,
                    'availability_type' => 'F',
                    'best_seats_only' => $product->bestseats,
                    'last_change_date' => '2000-01-01T19:00:00.0Z',
                    'event_date_begin' => $event_date_begin
                ];

                // $data = [
                //     "sales_type" => "F",
                //     "show_code" => "KIMAKIMBO",
                //     "show_city_code" => "NYCA",
                //     "event_date_end" => "2024-05-05",
                //     "availability_type" => "F",
                //     "best_seats_only" => 0,
                //     "last_change_date" => "2000-01-01T19:00:00.0Z",
                //     "event_date_begin" => "2024-03-24"
                // ];
    
                $service = new ServiceGeneral();
                $result = $service->availabilitySeat($data);

                // $result = $this->simulateResponse();

                if (isset($result['Error']) && $result['Error'] === 'No Data') {
                    echo 'no data'.PHP_EOL;
                    $to_delete[] = $product->product_id;
                } else {

                    $response_collection = collect($result);

                    // Verificar si la colección tiene datos
                    if ($response_collection->isNotEmpty()) {
    
                        $product_date =$product->product_date;
                        $product_time = $product->product_time;
                        $product_code = $product->product_code;
    
    
                        $exist = $response_collection->first(function ($item) use ($product_date, $product_time, $product_code) {
                            return $item['ProductDate'] == $product_date 
                            && $item['ProductTime'] == $product_time
                            && $item['ProductCode'] == $product_code;
                        });
    
                        if ($exist) {
                            //update price
                            echo 'update product '.$product->product_id.PHP_EOL;
                            $product->price = $exist['Price'];
                            $product->regular_price = $exist['RegularPrice'];
                            $product->currency = $exist['Currency'];
                            $product->bestseats = $exist['BestSeats'];
                            $product->availability = $exist['Availability'];
                            $product->base_price = $exist['BasePrice'];
                            $product->facility_fee = $exist['FacilityFee'];
                            $product->supplier_fee = $exist['SupplierFee'];
                            $product->save();
                        } else {
                            $to_delete[] = $product->product_id;
                        }
                    }
                }
            }
        }

        //delete products no finded
        if(isset($to_delete)){
            echo 'Delete products'.PHP_EOL;
            ProductSeat::whereIn('product_id', $to_delete)->delete();
        }
        return 'command';
    }

    private function simulateResponse($exists = true){
        if($exists){

            return [
                [
                    "ProductId" => "12374159",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "08/09/24",
                    "ProductTime" => "8:00PM",
                    "Description" => "Mezzanine Side Rows D-F",
                    "Price" => "99.40",
                    "RegularPrice" => "125.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "89.90",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12374553",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "08/09/24",
                    "ProductTime" => "2:00PM",
                    "Description" => "Orchestra Rows O-Q",
                    "Price" => "108.95",
                    "RegularPrice" => "135.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "99.45",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12374801",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "2:00PM",
                    "Description" => "Orchestra Rows G-P",
                    "Price" => "119.10",
                    "RegularPrice" => "155.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "109.60",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12452188",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "2:00PM",
                    "Description" => "Orchestra Side Rows A-H",
                    "Price" => "135.10",
                    "RegularPrice" => "175.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "125.60",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12452185",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "2:00PM",
                    "Description" => "Orchestra Rows AA-BB",
                    "Price" => "151.10",
                    "RegularPrice" => "195.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "141.60",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12374272",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "2:00PM",
                    "Description" => "Orchestra Center Rows J-N Mezzanine Side Rows A-C",
                    "Price" => "151.45",
                    "RegularPrice" => "185.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "141.95",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12374160",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "7:00PM",
                    "Description" => "Mezzanine Side Rows D-F",
                    "Price" => "78.40",
                    "RegularPrice" => "99.00",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "68.90",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12374273",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "7:00PM",
                    "Description" => "Orchestra Center Rows J-N",
                    "Price" => "142.95",
                    "RegularPrice" => "175.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "133.45",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ],
                [
                    "ProductId" => "12452186",
                    "ProductCode" => "KIMAKIMBO",
                    "ProductDate" => "03/24/24",
                    "ProductTime" => "7:00PM",
                    "Description" => "Orchestra Rows AA-BB",
                    "Price" => "145.50",
                    "RegularPrice" => "188.50",
                    "Currency" => "USD",
                    "BestSeats" => "1",
                    "Availability" => "Freesell",
                    "ETicketPrintDate" => "Tickets will be emailed to you on 3/10/2024.",
                    "BasePrice" => "136.00",
                    "FacilityFee" => "9.50",
                    "Tax" => "0.00",
                    "SupplierFee" => "0.00",
                    "ServiceCharge" => "0.00"
                ]
            ];
        } else {
            return [
                "Error" => "No Data"
            ];        
        }
    }
}
