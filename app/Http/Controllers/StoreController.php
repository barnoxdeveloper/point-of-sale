<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data Store";
        $items = Store::orderBy('name', 'ASC')->get();
        if($request->ajax()){
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="store_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('storeCode', function ($data) {
                                    return $data->store_code;
                                })
                                ->addColumn('name', function ($data) {
                                    return Str::ucfirst($data->name);
                                })
                                ->addColumn('location', function($data){
                                    return $data->location;
                                })
                                ->addColumn('status', function($data){
                                    return $data->status;
                                })
                                ->addColumn('action', function($data){
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'storeCode', 'name', 'location', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.store.index_store', compact('title'));
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
        // $type = $request->type;
        // $check = Store::find($id);

        $validator = Validator::make( $request->all(),[
            'name' => 'required|max:255',
            'store_code' => 'required|max:50|unique:stores,store_code',
            'location' => 'required|max:255',
            'description' => 'nullable|max:255',
            'status' => 'required|in:ACTIVE,NON-ACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'notif' => "Error!",
                'messages' => $validator->errors(),
            ]);
        } else {
            Store::updateOrCreate(['id' => $id],
            [
                'name' => Str::ucfirst($request->name),
                'slug' => Str::slug($request->name),
                'store_code' => Str::upper($request->store_code),
                'location' => $request->location,
                'description' => $request->description,
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
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Store::where('id', $id)->firstOrFail();
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Store::find($id);
        $item->delete();
        return response()->json(['code' => 1]);
    }

    public function deleteSelectedStore(Request $request)
    {
        $id = $request->id;
        Store::whereIn('id', $id)->delete();
        return response()->json(['code' => 1]);
    }
}
