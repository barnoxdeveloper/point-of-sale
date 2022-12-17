<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data Supplier";
        $items = Supplier::orderBy('name', 'ASC')->get();
        if($request->ajax()) {
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="supplier_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('name', function ($data) {
                                    return $data->name;
                                })
                                ->addColumn('contact', function($data) {
                                    return $data->contact;
                                })
                                ->addColumn('address', function($data) {
                                    return $data->address;
                                })
                                ->addColumn('status', function($data) {
                                    return $data->status;
                                })
                                ->addColumn('action', function($data) {
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" class="btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Deleted" data-id="'.$data->id.'" class="btn btn-danger btn-md delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'name', 'contact', 'address', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.supplier.index_supplier', compact('title'));
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
     * Supplier a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $validator = Validator::make( $request->all(),[
            'name' => 'required|max:255',
            'contact' => 'required|max:255',
            'address' => 'required|max:255',
            'status' => 'required|in:ACTIVE,NON-ACTIVE',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'notif' => "Error!",
                'messages' => $validator->errors(),
            ]);
        } else {
            Supplier::updateOrCreate(['id' => $id],
            [
                'name' => Str::ucfirst($request->name),
                'contact' => $request->contact,
                'address' => $request->address,
                'status' => $request->status,
            ]); 
            return response()->json(['code' => 200]);
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
        $item = Supplier::where('id', $id)->firstOrFail();
        return response()->json($item);
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
        $item = Supplier::find($id);
        $item->delete();
        return response()->json(['code' => 200]);
    }

    public function deleteSelectedSupplier(Request $request)
    {
        $id = $request->id;
        Supplier::whereIn('id', $id)->delete();
        return response()->json(['code' => 200]);
    }
}
