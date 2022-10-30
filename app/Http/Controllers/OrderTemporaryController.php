<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{OrderTemporary};

class OrderTemporaryController extends Controller
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
        $products = DB::table('products')->where('status', 'ACTIVE')->where('stock','!=', 0)->get(['product_code', 'name']);
        return view('pages.admin.order_temporary.create_order_temporary', compact('title', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrderTemporary  $orderTemporary
     * @return \Illuminate\Http\Response
     */
    public function show(OrderTemporary $orderTemporary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrderTemporary  $orderTemporary
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderTemporary $orderTemporary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrderTemporary  $orderTemporary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderTemporary $orderTemporary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrderTemporary  $orderTemporary
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderTemporary $orderTemporary)
    {
        //
    }
}
