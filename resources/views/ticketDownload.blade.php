<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Generate QR/Barcode </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <style>
        .line-top{
            border-top: 2px dotted #000000;
        }

        .line-bottom{
            border-bottom: 2px dotted #000000;
        }
        body{
            padding-top:25px
        }

    </style>
</head>

<body>
                        
                        {{-- This code will be implemented when template images are added to the ticket --}}
                    {{-- <div style="position:relative;">
                            @foreach ($data as $item)
                                    <div style="position: relative;margin-bottom:50px">
                                        <!-- Imagen del usuario -->
                                        <img src="{{$image}}" alt="Imagen del usuario">

                                        <!-- Código QR -->
                                        <div style="position: absolute; top: 5px; left: 500px;"> {!! DNS2D::getBarcodeHTML("$item->code", 'QRCODE',7,7) !!}</div>
                                    </div>
                                </div>
                            @endforeach
                    </div> --}}
                                



                <table class="table">
                    @foreach ($data as $item)
                        
                        @if($item->type == 'QR')
                    <tbody>
                    
                            <tr class="line-top">
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{$image}}" style="width: 100px; height: auto">
                                
                                </td>
                                <td>
                                    <strong>{{$ticket->title_en}}</strong><br>
                                    Expiration Date: {{\Carbon\Carbon::parse($item->expiration_date)->format('m/d/Y')}}<br>
                                    <span> - Present this e-ticket on your mobile to enter</span><br>
                                    <span> - Not validate for resale, No refunds or exchanges for cash</span><br>
                                    <span> - Cannot be combined with other offers</span><br>
                                </td>
                                <td>
                                    {!! DNS2D::getBarcodeHTML("$item->code", 'QRCODE',7,7) !!}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td><img src="{{$image_logo}}" style="width: 100px; height: auto"></td>
                            </tr>
                            
                            <tr class="line-bottom">
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                            </tr>
                            
                        @else
                            <tr class="line-top">
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                            </tr>
                            <tr style="margin-top:20px;padding-top:20px;">
                                <td>
                                    <img src="{{$image}}" style="width: 100px; height: 100px">
                                </td>
                                <td>
                                    <strong>{{$ticket->title_en}}</strong><br>
                                    Expiration Date: {{\Carbon\Carbon::parse($item->expiration_date)->format('m/d/Y')}}<br>
                                    <span> - Present this e-ticket on your mobile to enter</span><br>
                                    <span> - Not validate for resale, No refunds or exchanges for cash</span><br>
                                    <span> - Cannot be combined with other offers</span><br>
                                </td>
                                <td style="color:transparent">-</td>
                               
                            </tr>
                            <tr>
                                <td style="color:transparent">-</td>
                                <td>
                                    <div style="font-size:36px">{!! DNS1D::getBarcodeHTML("$item->code", 'C39',2,36) !!}</div>
                                </td>
                                <td><img src="{{$image_logo}}" style="width: 100px; height: auto"></td>
                            </tr>
                            <tr class="line-bottom">
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                                <td style="color:transparent">-</td>
                            </tr>
                        @endif
                    @endforeach
                    
                    </tbody>
                </table>
</body>
</html>