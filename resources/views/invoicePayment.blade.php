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
    body { font-family: 'Poppins', sans-serif;}
    .invoice-text {
        position: absolute;
        top: 16px; /* Ajusta la posición vertical según tus necesidades */
        left: 20px; /* Ajusta la posición horizontal según tus necesidades */
        font-family: 'Poppins', sans-serif; /* Fuente personalizada */
        font-weight: bold; /* Color del texto */
    }
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
                <br>
                <br>
        </div>
        <div style="margin: 0;
                box-sizing: border-box;
                padding: 0 0em 0 0em">
            <p style="margin: 0;
                box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">
        <div style="border-top: 2px solid #A3A3A3"></div>

            <p style="margin-top: 0.5em;
                box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">

                <div>
                    <span style="font-weight: bold;">Name: </span> <span style="color:#5D5D5F">{{$name_customer}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">Email: </span> <span style="color:#5D5D5F">{{$email_customer}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">Order Number: </span> <span style="color:#5D5D5F">{{$orderNumber}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">Order Date: </span> <span style="color:#5D5D5F">{{$orderDate}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">Total: </span> <span style="color:#5D5D5F">${{$amount}}</span>
                </div>
                @if($cash_type == true)
                <div>
                    <span style="font-weight: bold;">Cash: </span> <span style="color:#5D5D5F">${{$bill_data->credit}} </span>
                </div>
                @if($auth == true)
                    <div>
                        <span style="font-weight: bold;">Discount: </span> <span style="color:#5D5D5F">${{$discount}} </span>
                    </div>
                @endif
                <div>
                    <span style="font-weight: bold;">Debt:  </span> <span style="color:#5D5D5F">${{$bill_data->debit}}</span>
                </div> 
                <br>
                <div>
                    <span style="font-weight: bold;">Payment: </span> <span style="color:#5D5D5F">Cash</span>
                </div> 
                <br>
                <div style="border-top: 2px solid #A3A3A3"></div>

                @elseif($credit_type == true)
                
                <div>
                    <span style="font-weight: bold;">Total Paid: </span> <span style="color:#5D5D5F">${{$bill_data->total}} </span>
                </div>
                @if($auth == true)
                    <div>
                        <span style="font-weight: bold;">Discount: </span> <span style="color:#5D5D5F">${{$discount}} </span>
                    </div>
                @endif
                <br>
                <div>
                    <span style="font-weight: bold;">Payment: </span> <span style="color:#5D5D5F">Credit Card</span>
                </div> 
                <div>
                    <span style="font-weight: bold;">Card Type: </span> <span style="color:#5D5D5F">{{$bill_data->card_type}}</span>
                </div> 
                <div>
                    <span style="font-weight: bold;">Credit Card: </span> <span style="color:#5D5D5F">xxxx-xxxx-xxxx-{{$bill_data->last_four_digits}}</span>
                    
                </div> 
                <br>
                <div style="border-top: 2px solid #A3A3A3"></div>
                
                @endif
            </p>
            
            <p style="box-sizing: border-box;
                color: #5D5D5F;
                
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
                            <span style="text-decoration: underline;color: #5D5D5F">{{$subitem->ticket->title_kr}} </span>
                            @if($subitem->ticket->ticket_type == "Musicals & Shows")
                            <span style="color: #5D5D5F">- [Seating info: {{$subitem->seating_info}}] </span>
                            @elseif($subitem->ticket->ticket_type == "Guide Tour")
                            <span style="color: #5D5D5F">- [Scheduled date: {{\Carbon\Carbon::parse($subitem->rq_schedule_datetime)->format('d/m/Y h:i A') }}] </span>
                            @endif
                        </div>
                    @endforeach
                <br>

                <div style="border-top: 2px solid #A3A3A3"></div>
                <br>

                @endforeach
            </p>
        </div>
    </div>
    <div class="invoice-text">
        <h2>RECEIPT</h2>
    </div>
    
</body>

</html>
