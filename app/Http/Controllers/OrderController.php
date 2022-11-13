<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\{Product, Order, OrderDetail};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

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
                $items = Order::with('user')
                                ->whereBetween('date', [$request->start_date, $request->end_date])
                                ->get();
            } else {
                $items = Order::with('user')->orderBy('date', 'DESC')->get();
            }
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="order_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('date', function ($data) {
                                    return date("d-M-Y", strtotime($data->date));
                                })
                                ->addColumn('orderId', function($data){
                                    return $data->order_id;
                                })
                                ->addColumn('user', function($data){
                                    if ($data->user !== NULL) {
                                        return $data->user->name;
                                    }
                                })
                                ->addColumn('total', function($data){
                                    // return 'Rp. '.number_format($data->total,0,",",".");
                                    return $data->total;
                                })
                                ->addColumn('description', function($data){
                                    return $data->description;
                                })
                                ->addColumn('action', function($data){
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'date', 'orderId', 'user', 'total', 'description', 'action'])
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
            'total_bayar' => 'required|max:11'
        ]);
        $userId = Auth::user()->id;
        // Get Grand Total
        $grandTotal = DB::table('order_temporaries')->where('user_id', $userId)->sum('sub_total');
        // Get Total Bayar & Kembalian
        $totalBayar = str_replace(",","", $request->total_bayar);
        $discount = str_replace(",","", $request->discount);
        $kembalian = $totalBayar - ($grandTotal - $discount);
        // return $totalBayar;
        
        // Return Back If Total Bayar Kurang
        if ($totalBayar < ($grandTotal - $discount)) {
            return redirect()->back()->with('failed', 'Uang yang dibayarkan Kurang!!');
        }

        // Get Invoice
        $date = Carbon::now();
        $orderId = 'INV'.'-'.$date;
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
            $items = DB::table('order_temporaries')->where('user_id', $userId)->get();
            // return $items;
            foreach ($items as $item) {
                $data = array();
                $orderDetail['order_id'] = $success->order_id;
                $orderDetail['product_name'] = $item->product_name;
                $orderDetail['product_code'] = $item->product_code;
                $orderDetail['price'] = $item->product->price;
                $orderDetail['quantity'] = $item->quantity;
                $orderDetail['sub_total'] = $item->sub_total;
                $orderDetail['created_at'] = Carbon::now();
                $orderDetail['updated_at'] = Carbon::now();
                OrderDetail::insert($orderDetail);

                // update qty product
                // $products = Product::where('product_code', [$item->product_code])->get();
                $products = DB::table('products')->where('product_code', [$item->product_code])->get();
                foreach ($products as $product) {
                    $prd['stock'] = $product->stock - $item->quantity;
                    $product->update($prd);
                }
                // delete data di temprorary
                $items->each->delete();
            }
        }
        return redirect()->back()->with('success', 'Transaction Success!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
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
        $item->delete();
        return response()->json($item);
    }

    public function deleteSelectedUser(Request $request)
    {
        $id = $request->id;
        Order::whereIn('id', $id)->delete();
        return response()->json(['code' => 1]);
    }
}
