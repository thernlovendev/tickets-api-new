<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductSeat;
use App\Services\BroadwayMusicals\ServiceGeneral;
use Carbon\Carbon;

class DeleteProductSeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:productSeats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Product Seats';

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
        
        $group = ProductSeat::select('product_code')->groupBy('product_code')->get();
        $to_delete = collect([]);
        foreach ($group as $key => $musical) {
            $products_seat = ProductSeat::where('product_code', $musical['product_code'])->get();
            $maxAttempts = 3; // Número máximo de intentos para cada musical
            $attempts = 0;
            
            $today = Carbon::now()->addDay(1);
            $datetime_end = $today->clone()->endOfYear();
            $event_date_begin = $today->format('Y-m-d');
            $event_date_end = $datetime_end->format('Y-m-d');

            $data = [
                'sales_type' => 'F',
                'show_code' => $musical['product_code'],
                'show_city_code' => 'NYCA',
                'event_date_end' => $event_date_end,
                'availability_type' => 'F',
                'best_seats_only' => '0',
                'last_change_date' => '2000-01-01T19:00:00.0Z',
                'event_date_begin' => $event_date_begin
            ];

            do {
                try {
                    echo 'attempts: '.$attempts.PHP_EOL;
                    $service = new ServiceGeneral();
                    $result = $service->availabilitySeat($data);
                    // $result = $this->simulateResponse();

                    if(gettype($result) == 'object'){
                        $result = get_object_vars($result);
                    }
                    if (isset($result['Error']) && $result['Error'] === 'No Data') {
                        echo 'no hay data en el musical'.PHP_EOL;
                        // ProductSeat::where('product_code', $musical['product_code'])->delete();
                    } else if(isset($result['ProductCode'])){
                 
                        $response_collection = collect($result);
                        // Verificar si la colección tiene datos
                        if ($response_collection->isNotEmpty()) {
                            // Utiliza diffUsing para personalizar la comparación
                            $diff = $products_seat->filter(function ($itemA) use ($response_collection) {
                                // Filtra los elementos que no coinciden en productTime, ProductDate y productCode
                                return $response_collection->where('ProductTime', $itemA['product_time'])
                                    ->where('ProductDate', $itemA['product_date'])
                                    ->where('ProductCode', $itemA['product_code'])
                                    ->isEmpty();
                            });
                            $to_delete = $to_delete->merge($diff->pluck('product_id'));
                        }
                    }
                    echo 'finish musical: '.$musical['product_code'].PHP_EOL;
                    break;
                } catch (\Exception $e) {
                    // Manejar el error (puedes registrar o imprimir el error)
                    echo 'Error: ' . $e->getMessage() . PHP_EOL;
                    $attempts++;

                    if ($attempts >= $maxAttempts) {
                        // Si se alcanza el número máximo de intentos, abortar
                        break;
                    }
                    // Esperar antes de volver a intentar (puedes ajustar el tiempo de espera)
                    sleep(5); // Espera 5 segundos (ajusta según tus necesidades)
                }
            } while ($attempts < $maxAttempts); 

        }

        //delete products no finded
        if($to_delete->isNotEmpty()){
            echo 'Delete products'.PHP_EOL;

            ProductSeat::whereIn('product_id', $to_delete->toArray())->delete();
        }
    }

    private function simulateResponse($exists = true){
        if($exists){

            return [
                [
                    "ProductId" => "12374159",
                    "ProductCode" => "CHICAGO",
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
