<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator};

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
            if (!empty($request->start_date)) {
                $items = Order::with('user')
                                ->whereBetween('date', [$request->start_date, $request->end_date])
                                ->get();
            } else {
                $items = Order::with('user')->get();
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
        $id = $request->id;
        
        $validator = Validator::make( $request->all(),[
            'name' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'store_id' => 'nullable|exists:stores,id',
            'password' => 'max:50',
            'roles' => 'required|not_in:0|in:ADMINISTRATOR,MANAGER,CASHIER',
            'status' => 'required|in:ACTIVE,NON-ACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'notif' => "Error!",
                'messages' => $validator->errors(),
            ]);
        } else {
            Order::updateOrCreate(['id' => $id],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'store_id' => $request->store_id,
                        'roles' => $request->roles,
                        'status' => $request->status,
                    ]); 
            return response()->json([
                'code' => 200,
                'notif' => "Saved!",
                'messages' => "Your Data has been Saved!",
            ]);
        }
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
