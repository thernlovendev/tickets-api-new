<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>How to Generate QR Code in Laravel 8</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>

<body>

                <h2>Tickets for {{$reservation->customer_name_en}}</h2>
                <table class="table">
                    <tbody style="border-style: dotted;border-color: #000000;">
                    @foreach ($data as $item)
                        <tr style="margin-top:20px;padding-top:20px;">
                            <td>
                            <img src="{{storage_path().'/app/public/'.$image->path}}" style="width: 100px; height: 100px">
                            </td>
                            <td>
                                <strong>General Admission</strong><br>
                                <strong>{{$ticket->title_en}}</strong><br>
                                Expiration Date: {{\Carbon\Carbon::parse($item->expiration_date)->format('m/d/Y')}}
                            </td>
                            <td>
                                {!! DNS2D::getBarcodeHTML("$item->code", 'QRCODE',7,7) !!}
                            </td>
                        </tr>
                        <tr>
                            <td style="color:transparent">-</td>
                            <td style="color:transparent">-</td>
                            <td style="color:transparent">-</td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
</body>
</html>