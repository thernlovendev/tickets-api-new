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
                @foreach ($data as $item)
                {{$item}}
                {{-- <div class="mb-3">{!! DNS2D::getBarcodeHTML("$code", 'QRCODE') !!}</div> --}}
                    
                @endforeach
            </div>
        </div>

    </div>
</body>
</html>