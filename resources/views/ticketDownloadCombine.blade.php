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
            padding-top:25px;
            font-family: 'Poppins', sans-serif;
        }

    </style>
    @php
        $backgroundColor = '#FF0000'; // Establece el color de fondo
    @endphp
</head>

    <body>
            @if($type == 'QR')
            <!-- Código QR -->
            <div style="position: absolute; top: 92px; left: 571px;">
            <p>{!! $svgCode !!}</p>

                                                    
            </div>

            <div style="position: absolute; top: 240px; left: 552px;"> 
            <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
            {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
            
            </div>

            
            @elseif($type == 'Bar')
            <div style="position: absolute; top: 90px; left: 528px;"> 
            {{-- <span style="font-size:10px">{!! DNS1D::getBarcodeHTML("$code", 'C39',2,48) !!}</span> --}}

            <img width=178 height=50 src="data:image/png;base64,{{ DNS1D::getBarcodePNG("$code", 'C39') }}" alt="barcode" />
            </div>

            <div style="position: absolute; top: 138px; left: {{$bar_wid}}px;"> 
            <span style="font-weight:800">{{$code}}</span>
            
            </div>

            <div style="position: absolute; top: 155px; left: 550;"> 
                <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
                {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
            
            </div>
            @elseif($type == 'Text')

            <div style="position: absolute; top: 98px; left: {{$text_wid}}px;"> 
            <span style="font-weight:800">{{$code}}</span>
            
            </div>
            <div style="position: absolute; top: 128px; left:  552px;"> 
            <span style="font-weight:800">Expiration: {{$expiration_date}}</span><br>
            {{-- <span style="font-weight:800">{{$reservation->customer_name_en}}</span> --}}
            
            </div>


            @endif
                            
        <div style="position:relative;">
            <div style="position: relative;margin-bottom:50px">
                <!-- Imagen del usuario -->
                <img width=700 height=auto src="{{$image}}" alt="Imagen del usuario">
            </div>
        </div>
        </div>

    </body>
</html>