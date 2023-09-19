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
                <div style="position: absolute; top: 45px; left: 508px;"> {!! DNS2D::getBarcodeHTML("$code", 'QRCODE',7,7) !!}
                                                        
                </div>

                <div style="position: absolute; top: 200px; left: 498px;"> 
                <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
                
                </div>

                
                @elseif($type == 'Bar')
                <div style="position: absolute; top: 45px; left: 176px;"> 
                <span style="font-size:10px">{!! DNS1D::getBarcodeHTML("$code", 'C39',2,36) !!}</span>
                
                </div>

                <div style="position: absolute; top: 75px; left: 346px;"> 
                <span style="font-weight:800">{{$code}}</span>
                
                </div>

                <div style="position: absolute; top: 95px; left: 496px;"> 
                    <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                    {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
                
                </div>
                @elseif($type == 'Text')

                 <div style="position: absolute; top: 38px; left: 548px;"> 
                <span style="font-weight:800">{{$code}}</span>
                
                </div>
                <div style="position: absolute; top: 68px; left: 496px;"> 
                <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
                
                </div>


                @endif
            </div>
        </div>
        </div>

    </body>
</html>