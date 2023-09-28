<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link href="https://file.myfontastic.com/6tkDvaBT8S52S4nU8THupM/icons.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <title>{{__('Payment Successfully')}}</title>
    <style>
    body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body style="margin: 0;
    box-sizing: border-box;
    justify-content: center;
    align-items: center;">
    <div style="margin: 0 auto;
        box-sizing: border-box;
        width: 95%;
        height: 80%;
        background-color: #fff;
        border-radius: 5px;
        justify-content: center;
        padding-bottom: 1.2em;
        margin-bottom: 0.5em;
        min-width: 320px;">
        <div style="
        box-sizing: border-box;
        width: 100%;
        align-content: center;
        margin-bottom: 4em;margin-left: 38%;">
            <img style="margin: 0 auto;
                box-sizing: border-box;
                display: block;
                width: 100%;
                max-width: 150px;
                " src="https://tamice.com/site/images/tamice/logo/tamice-logo-225px.png" alt="logo-tamice">
        </div>
        <div style="margin: 0;
                box-sizing: border-box;
                padding: 0 0em 0 0em">
            <p style="margin: 0;
                box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">
            <p style="margin-top: 0.5em;
                box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">
                <div>
                    <span style="font-weight: bold;">Order Number: {{$orderNumber}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">Order Date: {{$orderDate}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">Total: ${{$amount}} </span>
                </div>
                @if($cash_type == true)
                <div>
                    <span style="font-weight: bold;">Cash: ${{$bill_data->credit}} </span>
                </div>
                @if($auth == true)
                    <div>
                        <span style="font-weight: bold;">Discount: ${{$discount}} </span>
                    </div>
                @endif
                <div>
                    <span style="font-weight: bold;">Debt: ${{$bill_data->debit}} </span>
                </div> 
                <br>
                <div>
                    <span style="font-weight: bold;">Payment: Cash</span>
                </div> 
                <br>
                <div style="border-top: 2px solid #A3A3A3"></div>

                @elseif($credit_type == true)
                
                <div>
                    <span style="font-weight: bold;">Total Paid: ${{$bill_data->total}} </span>
                </div>
                @if($auth == true)
                    <div>
                        <span style="font-weight: bold;">Discount: ${{$discount}} </span>
                    </div>
                @endif
                <br>
                <div>
                    <span style="font-weight: bold;">Payment: Credit Card</span>
                </div> 
                <div>
                    <span style="font-weight: bold;">Card Type: {{$bill_data->card_type}}</span>
                </div> 
                <div>
                    <span style="font-weight: bold;">Credit Card: </span>
                    <span>xxxx-xxxx-xxxx-{{$bill_data->last_four_digits}}</span>
                    
                </div> 
                <br>
                <div style="border-top: 2px solid #A3A3A3"></div>
                
                @endif
            </p>
            
            <p style="box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">

                @foreach($reservationItems as $item)
                    <div style="font-weight: bold;">

                    @isset($item->priceList)
                        <span>{{$item->priceList->product_type}} </span>
                    @endisset
                       <span>(Price: ${{$item->total}}, Quantity: {{$item->quantity}})</span>
                    </div>
                    <br>
                    @foreach($item->reservationSubItems as $subitem)
                        <div style="margin-left:1rem">
                            <span style="text-decoration: underline;">{{$subitem->ticket->title_en}}</span>
                        </div>
                    @endforeach
                <br>

                <div style="border-top: 2px solid #A3A3A3"></div>
                <br>

                @endforeach
            </p>
        </div>
    </div>
    
</body>

</html>
