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
                            
        <div style="position:relative;">
            <div style="position: relative;margin-bottom:50px">
                <!-- Imagen del usuario -->
                <img width=700 height=auto src="{{$image}}" alt="Imagen del usuario">
                @if($type == 'QR')
                <!-- Código QR -->
                <div style="position: absolute; top: 30px; left: 520px;"> {!! DNS2D::getBarcodeHTML("$code", 'QRCODE',7,7) !!}
                                                        
                </div>

                <div style="position: absolute; top: 200px; left: 510px;"> 
                <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
                
                </div>

                
                @elseif($type == 'Bar')
                <div style="position: absolute; top: 35px; left: 380px;"> 
                <span style="font-size:10px">{!! DNS1D::getBarcodeHTML("$code", 'C39',2,36) !!}</span>
                
                </div>

                <div style="position: absolute; top: 65px; left: 490px;"> 
                <span style="font-weight:800">{{$code}}</span>
                
                </div>

                <div style="position: absolute; top: 95px; left: 510px;"> 
                    <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                    {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
                
                </div>
                @elseif($type == 'Text')

                 <div style="position: absolute; top: 30px; left: 608px;"> 
                <span style="font-weight:800">{{$code}}</span>
                
                </div>
                <div style="position: absolute; top: 65px; left: 510px;"> 
                <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
                
                </div>


                @endif
            </div>
        </div>
        </div>

    </body>
</html>