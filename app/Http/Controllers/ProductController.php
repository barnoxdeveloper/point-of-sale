<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data Product";
        $items = Product::with('store', 'category')->orderBy('roles', 'ASC')->get();
        $categories = DB::table('categories')->orderBy('name', 'ASC')->get();
        if($request->ajax()){
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="product_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('name', function($data) {
                                    return $data->name;
                                })
                                ->addColumn('category', function($data){
                                    if ($data->category !== NULL) {
                                        return Str::upper($data->category->name);
                                    }
                                })
                                ->addColumn('price', function($data) {
                                    return '<del>'.$data->old_price.'</del> | '.$data->new_price.'';
                                })
                                ->addColumn('stock', function($data) {
                                    return '<span class="text-danger">'.$data->limit_stock.'</span> | <span class="text-danger">'.$data->stock.'</span>';
                                })
                                ->addColumn('status', function($data) {
                                    return $data->status;
                                })
                                ->addColumn('action', function($data) {
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'name', 'category', 'price', 'stock', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.product.index_product', compact('title', 'categories'));
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
            'category_id' => 'required|exists:categories,id',
            'old_price' => 'required|digits:11',
            'new_price' => 'required|digits:11',
            'limit_stock' => 'required|digits:11',
            'stock' => 'required|digits:11',
            'description' => 'required|max:255',
            'photo' => 'nullable|max:255',
            'status' => 'required|in:ACTIVE,NON-ACTIVE',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'notif' => "Error!",
                'messages' => $validator->errors(),
            ]);
        } else {
            $file = $request->file('photo');
            $nama_file = $file->getClientOriginalName();
            $check = File::exists('storage/assets/category/'.$nama_file);
            if ($type == "create" && $request->photo !== NULL) { // create with photo
                if ($check) {
                    $photo = NULL;
                    $message = "Photo is Already Exists, Please Change Photo!";
                } else {
                    $photo = $file->storeAs('assets/category',$nama_file,'public');
                    $message = "Data Create Successfully!";
                }
            } else if ($type == 'edit' && $request->photo !== NULL) { // edit with photo
                if ($check) {
                    $photo = $categoryProductCheck->getRawOriginal('photo');
                    $message = "Photo is Already Exists, Please Change Photo!";
                } else {
                    File::delete('storage/'. $categoryProductCheck->getRawOriginal('photo'));
                    $photo = $file->storeAs('assets/category',$nama_file,'public');
                    $message = "Data Updated With Photo Successfully!";
                }
            } else if ($type == "edit" && $request->photo == NULL) { // edit without photo
                $photo = $categoryProductCheck->getRawOriginal('photo');
                $message = "Data Updated Without Photo Successfully!";
            }
            
            Product::updateOrCreate(['id' => $id],
                    [
                        'name' => $request->name,
                        'category_id' => $request->category_id,
                        'store_id' => $request->category()->store_id,
                        'old_price' => $request->old_price,
                        'new_price' => $request->new_price,
                        'limit_stock' => $request->limit_stock,
                        'stock' => $request->stock,
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Product::where('id', $id)->firstOrFail();
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Product::find($id);
        $item->delete();
        return response()->json($item);
    }

    public function deleteSelectedProduct(Request $request)
    {
        $id = $request->id;
        Product::whereIn('id', $id)->delete();
        return response()->json(['code' => 1]);
    }
