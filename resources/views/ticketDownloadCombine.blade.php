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
                        <div style="position:relative;">
                            <div style="position: relative;margin-bottom:50px">
                                <!-- Imagen del usuario -->
                                <img width=400 height=auto src="{{$image}}" alt="Imagen del usuario">

                                @if($type == 'QR')
                                <!-- Código QR -->
                                <div style="position: absolute; top: 5px; left: 500px;"> {!! DNS2D::getBarcodeHTML("$code", 'QRCODE',7,7) !!}
                                
                                <br>
                                <span style="font-weight:800">Expiration Date: {{$expiration_date}}</span>
                                </div>
                                @elseif($type == 'Bar')
                                <div style="position: absolute; top: 5px; left: 420px;"> 
                                {!! DNS1D::getBarcodeHTML("$code", 'C39',2,36) !!}
                                <br>
                                <span style="font-weight:800">Expiration Date: {{$expiration_date}}</span>
                                
                                </div>

                                @elseif($type == 'Text')
                                <div style="position: absolute; top: 5px; left: 500px;"> 
                                <span style="font-weight:800">{{$code}}</span>
                                <br>
                                <span style="font-weight:800">Expiration Date: {{$expiration_date}}</span>
                                
                                </div>

                                @endif
                            </div>
                        </div>
                    </div>
                            
</body>
</html>