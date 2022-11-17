<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
        .text-center {
            text-align: center;
            font-size: 12px
        }

        .text-center .border {
            border: 1px solid;
            margin: 2px 3px;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            @foreach ($barcodeName as $item)
                <td class="text-center">
                    <div class="border">
                        {{ $item->name }}
                        <br>
                        Rp. {{ number_format($item->new_price,0,",",".") }}
                        <br>
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->product_code, 'C39') }}" 
                            alt="Barcode"
                            width="140"
                            height="40" 
                            style="margin: 3px 0">
                        <br>
                        {{ $item->product_code }}
                    </div>
                </td>
                @if($number++ % 3 == 0)
                </tr><tr>
                @endif
            @endforeach
        </tr>
    </table>
</body>
</html>