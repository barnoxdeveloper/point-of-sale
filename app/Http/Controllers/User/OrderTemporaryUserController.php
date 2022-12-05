<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\OrderTemporary;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB};

class OrderTemporaryUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Create Order";
        $products = DB::table('products')
                        ->where('store_id', Auth::user()->store_id)
                        ->where('status', 'ACTIVE')
                        ->where('stock','!=', 0)
                        ->get(['product_code', 'name']);
        $items = DB::table('order_temporaries')->where('user_id', Auth::user()->id)->get();
        $i = 1;
        $grandTotal = DB::table('order_temporaries')->where('user_id', Auth::user()->id)->sum('sub_total');
        // Get Invoice
        $date = Carbon::now();
        $orderId = 'INV'.'-'.str_replace(" ","-", $date);        
        return view('pages.user.order_temporary.create_order_temporary', compact('title', 'products', 'items', 'i', 'grandTotal', 'orderId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $productCode = $request->product;
        // $product = Product::where('product_code', 'like', '%'. $productCode .'%')
        //                     ->orWhere('name', 'like', '%'. $productCode .'%')
        //                     ->first();
        $product = DB::table('products')->where('product_code', $productCode)
                        ->orWhere('name', $productCode)
                        ->first();
        if (!$product || $product->stock == 0) {
            return redirect()->back()->with('failed', 'Product Tidak Ada!');
        } else if ($product) {
            // $check = OrderTemporary::where('product_code', $productCode)->first();
            $check = DB::table('order_temporaries')
                        ->where('product_code', $productCode)
                        ->where('product_name', $productCode)
                        ->where('user_id', Auth::user()->id)
                        ->first();
            if ($check) {
                return redirect()->back()->with('success', 'Product Sudah ada di keranjang, silahkan update quantity');
            } else {
                $data['user_id'] = Auth::user()->id;
                $data['product_code'] = $product->product_code;
                $data['product_name'] = $product->name;
                $data['price'] = $product->new_price;
                $data['quantity'] = 1;
                $data['sub_total'] = $product->new_price * 1;
                OrderTemporary::create($data);
                if ($product->stock < 10) {
                    return redirect()->back()->with('success', 'Stock Product di bawah 10');   
                } else {
                    return redirect()->back()->with('success', 'Berhasil di Tambahkan');
                }
            }
        } else{
            return redirect()->back()->with('failed', 'Product Tidak di Temukan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $this->validate($request, ['quantity' => 'required|not_in:0|digits_between:1,11']);
        $item = OrderTemporary::findOrFail(decrypt($id));
        if ($item->product->stock < $request->quantity) {
            return redirect()->back()->with('failed', 'Stock Product hanya : '.$item->product->stock);    
        } else {
            $data['quantity'] = $request->quantity;
            $data['sub_total'] = $item->product->new_price * $request->quantity;
            $item->update($data);
            return redirect()->back()->with('success', 'Quantity Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = OrderTemporary::find(decrypt($id));
        $item->delete();
        return redirect()->back()->with('success', 'Product Deleted!');
    }
}
