<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\{Order, Product, OrderDetail, OrderTemporary};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth};
use App\Http\Controllers\Controller;

class OrderUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data Order";
        if($request->ajax()) {
            if (Auth::user()->is_roles_cashier) {
                if (!empty($request->startDate)) {
                    $items = Order::with(['store', 'user', 'orderDetail'])
                                    ->where('store_id', Auth::user()->store_id)
                                    ->where('user_id', Auth::user()->id)
                                    ->whereBetween('date', [$request->startDate, $request->endDate])
                                    ->orderBy('date', 'DESC')
                                    ->get();
                } else {
                    $items = Order::with(['store', 'user', 'orderDetail'])
                                    ->where('store_id', Auth::user()->store_id)
                                    ->where('user_id', Auth::user()->id)
                                    ->orderBy('date', 'DESC')
                                    ->get();

                }
            } else if (Auth::user()->is_roles_manager) {
                if (!empty($request->startDate)) {
                    $items = Order::with(['store', 'user', 'orderDetail'])
                                    ->where('store_id', Auth::user()->store_id)
                                    ->whereBetween('date', [$request->startDate, $request->endDate])
                                    ->orderBy('date', 'DESC')
                                    ->get();
                } else {
                    $items = Order::with(['store', 'user', 'orderDetail'])
                                    ->where('store_id', Auth::user()->store_id)
                                    ->orderBy('date', 'DESC')
                                    ->get();

                }
            }
            return datatables()->of($items)
                                ->addColumn('date', function ($data) {
                                    return date("d-M-Y", strtotime($data->date));
                                })
                                ->addColumn('orderId', function($data) {
                                    return $data->order_id;
                                })
                                ->addColumn('user', function($data) {
                                    return $data->user !== null ? $data->user->name : '';
                                })
                                ->addColumn('total', function($data) {
                                    return $data->total;
                                    // return 'Rp. '.number_format($data->total,0,",",".");
                                })
                                ->addColumn('description', function($data) {
                                    return $data->description;
                                })
                                ->addColumn('action', function($data) {
                                    $url = route('print-invoice-user',$data->order_id);
                                    $button = '<a href="'.$url.'" title="Print" class="btn btn-success btn-md" target="_blank"><i class="fa fa-print"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Details" data-id="'.$data->id.'" class="btn btn-primary btn-md btn-detail">'.$data->orderDetail->count().'  <i class="fa fa-box-open"></i></a>';
                                    // $button .= '&nbsp;&nbsp;';
                                    // $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['date', 'orderId', 'user', 'total', 'description', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.user.order.index_order', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'discount' => 'nullable|max:11',
            'total_bayar' => 'required|not_in:0|max:11'
        ]);
        $userId = Auth::user()->id;
        // Get Grand Total
        $grandTotal = DB::table('order_temporaries')->where('user_id', $userId)->sum('sub_total');
        // Get Total Bayar & Kembalian
        $totalBayar = str_replace(",","", $request->total_bayar);
        $discount = Auth::user()->store->discount;
        $kembalian = $totalBayar - ($grandTotal - $discount);
        // Return Back If Total Bayar Kurang
        if ($totalBayar < ($grandTotal - $discount)) {
            return redirect()->back()->with('failed', 'Uang yang dibayarkan Kurang!!');
        }
        // Get Invoice
        $date = Carbon::now();
        $orderId = 'INV'.'-'.str_replace(" ","-", $date);
        $data['store_id'] = Auth::user()->store_id;
        $data['user_id'] = $userId;
        $data['order_id'] = $orderId;
        $data['total'] = $grandTotal;
        $data['date'] = $date;
        $data['discount'] = $discount;
        $data['total_bayar'] = $totalBayar;
        $data['kembalian'] = $kembalian;
        // $data['descriptions'] = $request->descriptions;
        $success = Order::create($data);
        if ($success) {
            $items = OrderTemporary::where('user_id', $userId)->get();
            foreach ($items as $item) {
                $data = array();
                $orderDetail['order_id'] = $success->order_id;
                $orderDetail['product_name'] = $item->product_name;
                $orderDetail['product_code'] = $item->product_code;
                $orderDetail['price'] = $item->price;
                $orderDetail['quantity'] = $item->quantity;
                $orderDetail['sub_total'] = $item->sub_total;
                $orderDetail['created_at'] = Carbon::now();
                $orderDetail['updated_at'] = Carbon::now();
                OrderDetail::insert($orderDetail);
                // update qty product
                // $products = Product::where('product_code', [$item->product_code])->get();
                $products = Product::where('product_code', [$item->product_code])->get();
                foreach ($products as $product) {
                    $prd['stock'] = $product->stock - $item->quantity;
                    $product->update($prd);
                }
                // delete data di temprorary
                $items->each->delete();
            }
        }
        return redirect()->back()->with('success_invoices', $success);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Order::with(['user', 'orderDetail'])->where('id', $id)->firstOrFail();
        return response()->json($item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $item = Order::find($id);
        // OrderDetail::where('order_id', $item->order_id)->delete();
        // $item->delete();
        // return response()->json(['code' => 200]);
    }

    public function printInvoice($id)
    {
        $order = Order::where('order_id', $id)->first();
        $orderDetail = OrderDetail::where('order_id', $order->order_id)->get();
        return view('pages.user.order.print_invoice', compact('order', 'orderDetail'));
    }
}
