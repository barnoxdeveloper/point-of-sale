<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title></title>
        
        <style>
            .invoice-box {
                max-width: 300px;
                /* margin: auto;
                padding: 18px; */
                border: 1px solid #eee;
                box-shadow: 0 0 8px rgba(0, 0, 0, .15   );
                font-size: 8px;
                /* line-height: 16px; */
                font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                color: #000000;
                /* margin-top: 10px; */
                font-weight: 700;
            }
            
            .invoice-box .information {
                text-align: center;
            }

            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }
            
            
        </style>
    </head>

    <body>
        <div class="invoice-box">
            <div class="information">
                <img src="{{ url('backend/images/logo.png') }}" style="width:50px; width:50px; text-align: center">
                <pre style="text-align: center">ABC STORE</pre>
                <pre style="text-align: center">Terima Kasih Sudah Berbelanja</pre>
                <pre>{{ date("H:i d/m/Y", strtotime($order->created_at)) }}</pre>
                <pre>{{ $order->order_id }}</pre>
            </div>
            <table cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                    <tr class="heading">
                        <td>Item</td>
                        <td>Qty</td>
                        <td>Harga</td>
                        <td>SubTotal</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                    @foreach($orderDetail as $item)
                    <tr class="item">
                        <td>{{ Str::substr($item->product_name, 0, 10) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp. {{ number_format($item->price,0,",",".") }}</td>
                        <td>Rp. {{ number_format($item->sub_total,0,",",".") }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total</b></td>
                        <td>Rp. {{number_format($order->total,0,",",".")}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Discount</b></td>
                        <td>Rp. {{number_format($order->discount,0,",",".")}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Bayar</b></td>
                        <td>Rp. {{number_format($order->total_bayar,0,",",".")}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Kembalian</b></td>
                        <td>Rp. {{number_format($order->kembalian,0,",",".")}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <script>
            window.onload = function(){
                window.print();
                window.onafterprint = function () {
                    window.close();
                }
            }
        </script>
    </body>
</html>