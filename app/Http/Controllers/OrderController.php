<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\{Product, Order, OrderDetail, OrderTemporary};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data Order";
        if($request->ajax()){
            if (!empty($request->startDate)) {
                $items = Order::with(['store', 'user', 'orderDetail'])
                                ->whereBetween('date', [$request->startDate, $request->endDate])
                                ->orderBy('date', 'DESC')
                                ->get();
            } else {
                $items = Order::with(['store', 'user', 'orderDetail'])->orderBy('date', 'DESC')->get();
            }
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="order_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('date', function ($data) {
                                    return date("d-M-Y", strtotime($data->date));
                                })
                                ->addColumn('orderId', function($data) {
                                    return $data->order_id;
                                })
                                ->addColumn('store', function($data) {
                                    return $data->store !== null ? $data->store->name : '';
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
                                    $url = route('print-invoice',$data->order_id);
                                    $button = '<a href="'.$url.'" title="Print" class="btn btn-success btn-md" target="_blank"><i class="fa fa-print"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Details" data-id="'.$data->id.'" class="btn btn-primary btn-md btn-detail">'.$data->orderDetail->count().'  <i class="fa fa-box-open"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Deleted" data-id="'.$data->id.'" class="btn btn-danger btn-md delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'date', 'orderId', 'store', 'user', 'total', 'description', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.order.index_order', compact('title'));
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
        $discount = str_replace(",","", $request->discount);
        $kembalian = $totalBayar - ($grandTotal - $discount);
        // Return Back If Total Bayar Kurang
        if ($totalBayar < ($grandTotal - $discount)) {
            return redirect()->back()->with('failed', 'Uang yang dibayarkan Kurang!!');
        }
        // Get Invoice
        $date = Carbon::now();
        $orderId = 'INV'.'-'.str_replace(" ","-", $date);
        $data['store_id'] = $request->store_id;
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
            // return $items;
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
     * @param  \App\Models\Order  $order
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
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Order::find($id);
        OrderDetail::where('order_id', $item->order_id)->delete();
        $item->delete();
        return response()->json(['code' => 200]);
    }

    public function deleteSelectedOrder(Request $request)
    {
        $id = $request->id;
        $items = Order::whereIn('id', $id)->get();
        foreach ($items as $key => $item) {
            OrderDetail::where('order_id', $item->order_id)->delete();
        }
        Order::whereIn('id', $id)->delete();
        return response()->json(['code' => 200]);
    }

    public function printInvoice($id)
    {
        $order = Order::where('order_id', $id)->first();
        $orderDetail = OrderDetail::where('order_id', $order->order_id)->get();
        return view('pages.admin.order.print_invoice', compact('order', 'orderDetail'));
    }

    public function orderWhereStore(Request $request, $id)
    {
        $store = DB::table('stores')->where('id', decrypt($id))->first();
        $title = "Data Order (Store : ". $store->name . ")";
        if($request->ajax()){
            if (!empty($request->startDate)) {
                $items = Order::with(['user', 'orderDetail'])
                                ->where('store_id', decrypt($id))
                                ->whereBetween('date', [$request->startDate, $request->endDate])
                                ->orderBy('date', 'DESC')
                                ->get();
            } else {
                $items = Order::with(['user', 'orderDetail'])
                                ->where('store_id', decrypt($id))
                                ->orderBy('date', 'DESC')
                                ->get();
            }
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="order_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
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
                                    $url = route('print-invoice',$data->order_id);
                                    $button = '<a href="'.$url.'" title="Print" class="btn btn-success btn-md" target="_blank"><i class="fa fa-print"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Details" data-id="'.$data->id.'" class="btn btn-primary btn-md btn-detail">'.$data->orderDetail->count().'  <i class="fa fa-box-open"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Deleted" data-id="'.$data->id.'" class="btn btn-danger btn-md delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'date', 'orderId', 'user', 'total', 'description', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.order.order_where_store', compact('title', 'id'));
    }

    public function orderWhereUser(Request $request, $id)
    {
        $user = DB::table('users')->where('id', decrypt($id))->first();
        $title = "Data Order (User : ". $user->name . ")";
        if($request->ajax()){
            if (!empty($request->startDate)) {
                $items = Order::with(['user', 'orderDetail'])
                                ->where('user_id', decrypt($id))
                                ->whereBetween('date', [$request->startDate, $request->endDate])
                                ->orderBy('date', 'DESC')
                                ->get();
            } else {
                $items = Order::with(['user', 'orderDetail'])
                                ->where('user_id', decrypt($id))
                                ->orderBy('date', 'DESC')
                                ->get();
            }
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="order_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
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
                                    $url = route('print-invoice',$data->order_id);
                                    $button = '<a href="'.$url.'" title="Print" class="btn btn-success btn-md" target="_blank"><i class="fa fa-print"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Details" data-id="'.$data->id.'" class="btn btn-primary btn-md btn-detail">'.$data->orderDetail->count().'  <i class="fa fa-box-open"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Deleted" data-id="'.$data->id.'" class="btn btn-danger btn-md delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'date', 'orderId', 'user', 'total', 'description', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.order.order_where_user', compact('title', 'id'));
    }
}
