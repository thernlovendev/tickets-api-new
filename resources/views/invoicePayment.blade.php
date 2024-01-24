<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- <link href="https://file.myfontastic.com/6tkDvaBT8S52S4nU8THupM/icons.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;400&display=swap" rel="stylesheet">

    <title>{{__('Payment Successfully')}}</title>
    <style>
    body { font-family: 'Poppins', sans-serif, 'Noto Sans KR',}
    .invoice-text {
        font-family: 'Poppins', sans-serif; /* Fuente personalizada */
        font-weight: bold; /* Color del texto */
        margin-top:-40px;
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
        margin-left: 38%;">
            <img style="margin: 0 auto;
                box-sizing: border-box;
                display: block;
                width: 100%;
                max-width: 150px;
                " src="https://tamice.com/site/images/tamice/logo/tamice-logo-225px.png" alt="logo-tamice">
                <br>
                <br>
        </div>
        <div class="invoice-text" >
            <h2>RECEIPT</h2>
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
                    <span style="font-weight: bold;">성함: </span> <span style="color:#5D5D5F">{{$name_customer}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">이메일: </span> <span style="color:#5D5D5F">{{$email_customer}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">주문번호: </span> <span style="color:#5D5D5F">{{$orderNumber}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">주문날짜: </span> <span style="color:#5D5D5F">{{$orderDate}}</span>
                </div>
                <div>
                    <span style="font-weight: bold;">총금액: </span> <span style="color:#5D5D5F">${{$amount}}</span>
                </div>
                @if($cash_type == true)
                <div>
                    <span style="font-weight: bold;">결제금액: </span> <span style="color:#5D5D5F">${{$bill_data->credit}} </span>
                </div>
                @if($auth == true)
                    <div>
                        <span style="font-weight: bold;">할인 금액: </span> <span style="color:#5D5D5F">${{$discount}} </span>
                    </div>
                @endif
                <div>
                    <span style="font-weight: bold;">차액:  </span> <span style="color:#5D5D5F">${{$bill_data->debit}}</span>
                </div> 
                <br>
                <div>
                    <span style="font-weight: bold;">지불방법: </span> <span style="color:#5D5D5F">현금</span>
                </div> 
                <br>
                <div style="border-top: 2px solid #A3A3A3"></div>

                @elseif($credit_type == true)
                
                <div>
                    <span style="font-weight: bold;">지불한 금액: </span> <span style="color:#5D5D5F">${{$bill_data->total}} </span>
                </div>
                @if($auth == true)
                    <div>
                        <span style="font-weight: bold;">할인 금액: </span> <span style="color:#5D5D5F">${{$discount}} </span>
                    </div>
                @endif
                <br>
                <div>
                    <span style="font-weight: bold;">지불방법: </span> <span style="color:#5D5D5F">크레딧 카드</span>
                </div> 
                <div>
                    <span style="font-weight: bold;">카드타입: </span> <span style="color:#5D5D5F">{{$bill_data->card_type}}</span>
                </div> 
                <div>
                    <span style="font-weight: bold;">크레딧 카드: </span> <span style="color:#5D5D5F">xxxx-xxxx-xxxx-{{$bill_data->last_four_digits}}</span>
                    
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
                       <span>(가격: ${{$item->total}}, 수량: {{$item->quantity}})</span>
                    </div>
                    <br>
                    @foreach($item->reservationSubItems as $subitem)
                        <div style="margin-left:1rem">
                            <a href="{{env('APP_URL_WEB_PAGE')}}/product-detail/{{$subitem->ticket_id}}" style="text-decoration: underline;color: #5D5D5F">{{$subitem->ticket->title_kr}} </a>
                            @if($subitem->ticket->ticket_type == "Musicals & Shows")
                            <span style="color: #5D5D5F">- [좌석정보: {{$subitem->seating_info}}] - [쇼 시간: {{\Carbon\Carbon::parse($subitem->rq_schedule_datetime)->format('m/d/Y h:i A') }}]</span>
                            @elseif($subitem->ticket->ticket_type == "Guide Tour")
                            <span style="color: #5D5D5F">- [예정일: {{\Carbon\Carbon::parse($subitem->rq_schedule_datetime)->format('m/d/Y h:i A') }}] </span>
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
    
</body>

</html>
