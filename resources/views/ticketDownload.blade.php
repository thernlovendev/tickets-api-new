<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>How to Generate QR Code in Laravel 8</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>

<body>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2>Simple Barcode</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                    @foreach ($data as $item)
                        <tr style="margin-top:20px;padding-top:20px">
                            <td>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Laravel_Logo.svg/300px-Laravel_Logo.svg.png" alt="gallery Pic" >
                            
                            Gallery
                            </td>
                            <td>
                                {{$ticket->title_en}}
                                {{\Carbon\Carbon::parse($item->expiration_date)->format('m/d/Y')}}
                            </td>
                            <td>
                                {!! DNS2D::getBarcodeHTML("$item->code", 'QRCODE',7,7) !!}
                            </td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>