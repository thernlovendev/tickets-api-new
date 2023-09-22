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
    body { font-family: poppins; }
    </style>
</head>

<body style="margin: 0;
    box-sizing: border-box;
    justify-content: center;
    align-items: center;">
    <div style="margin: 0 auto;
        box-sizing: border-box;
        width: 80%;
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
            <p style="margin-top: 2em;
                box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">
                <div>
                    <span>Order Number: {{$orderNumber}}</span>
                </div>
                <div>
                    <span>Order Date: {{$orderDate}}</span>
                </div>
            </p>
            
            <p style="margin-top: 2em;
                box-sizing: border-box;
                color: #9A9A9A;
                
                font-size: 0.9em;
                line-height: 1.5">
                <table class="default">
                @foreach($reservationItems as $item)
                    <tr>
                        <th style="">Ticket Name</th>
                        <th style="">Adult/Child</th>
                        <th style="">Scheduled Date</td>
                        <th style="">Price</td>
                        <th style="">Quantity</td>
                        <th style="">Addition</td>
                        <th style="">Subtotal</td>
                    </tr>

                    <tr>
                        <td>{{$item->category->name}} - {{$item->subcategory->name}}</td>
                        <td>Celda 5</td>
                        <td>Celda 6</td>
                        <td>Celda 4</td>
                        <td>Celda 5</td>
                        <td>Celda 6</td>
                        <td>Celda 6</td>
                    </tr>
                    @foreach($item->reservationSubItems as $subitem)

                        <tr>
                            <th style="">Header subitem</th>
                            <th style="">Adult/Child</th>
                            <th style="">Scheduled Date</td>
                            <th style="">Price</td>
                            <th style="">Quantity</td>
                            <th style="">Addition</td>
                            <th style="">Subtotal</td>
                        </tr>
                        <tr>
                            <td>{{$subitem->ticket->title_en}}</td>
                            <td>Celda 5</td>
                            <td>Celda 6</td>
                            <td>Celda 4</td>
                            <td>Celda 5</td>
                            <td>Celda 6</td>
                            <td>Celda 6</td>
                        </tr>
                    @endforeach

                @endforeach

                
                </table>
            </p>
                <footer style="margin: 0;
                        box-sizing: border-box;
                        width: 100%;
                        justify-content: center;
                        border-top: 1px solid #A3A3A3;
                        padding-top: 1em;
                        justify-content: center; ">
                    <div style="margin: 0 auto;
                        box-sizing: border-box;
                        display: inline-block;
                        float: center; margin-left: 35%;">
                        <a style="margin: 0;
                        box-sizing: border-box;
                        color:#000000;
                        text-decoration: none;
                        font-weight: bold;
                        font-size: 0.8em;
                        margin-right: 0.5em" href="#">
                            <span>2023 Privace Content Footer</span>
                        </a>
                    </div>
                </footer>
        </div>
    </div>
    
</body>

</html>
