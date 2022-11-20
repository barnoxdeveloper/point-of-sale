<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Product, Category};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\{DB, Validator, File};

class ProductUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "All Products";
        $categories = Category::where('store_id', Auth::user()->store_id)->get();
        foreach ($categories as $key => $item) {
            $categoryId[] = $item->id;
        }
        $items = Product::with('category')->whereIn('category_id', $categoryId)->get();
        
        if($request->ajax()) {
            return datatables()->of($items)
                                ->addColumn('name', function($data) {
                                    return $data->product_code.' - '.$data->name;
                                })
                                ->addColumn('category', function($data){
                                    if ($data->category !== NULL) {
                                        return Str::upper($data->category->name);
                                    }
                                })
                                ->addColumn('price', function($data) {
                                    return '<del>'.number_format($data->old_price,0,",",".").'</del> | '.number_format($data->new_price,0,",",".").'';
                                })
                                ->addColumn('stock', function($data) {
                                    return '<span class="text-danger">'.$data->limit_stock.'</span> | <span class="text-success">'.$data->stock.'</span>';
                                })
                                ->addColumn('photo', function ($data) {
                                    if ($data->getRawOriginal('photo') !== NULL) {
                                        return '<a href="'.$data->photo.'" title="'.$data->photo.'" target="_blank><img src="'.$data->photo.'" alt="'.$data->photo.'" style="width: 100px; height: 100px;"><img src="'.$data->photo.'" alt="'.$data->photo.'" style="width: 100px; height: 100px;"></a>';    
                                    }
                                })
                                ->addColumn('status', function($data) {
                                    return $data->status;
                                })
                                ->addColumn('action', function($data) {
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Cetak Barcode" data-id="'.$data->id.'" data-original-title="Cetak Barcode" class="btn btn-success btn-md btn-barcode"><i class="fas fa-barcode"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    // $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['name', 'category', 'price', 'stock', 'photo', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.user.product.index_product', compact('title', 'categories'));
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
        $type = $request->metode;
        $productCheck = Product::find($id);
        $validator = Validator::make( $request->all(),[
            'product_code' => 'nullable|max:255',
            'name' => 'required|max:50',
            'category_id' => 'required|exists:categories,id',
            'old_price' => 'required|digits_between:0,11',
            'new_price' => 'required|digits_between:0,11',
            'limit_stock' => 'required|digits_between:0,11',
            'stock' => 'required|digits_between:0,11',
            'type' => 'required|in:PCS,PACK,KILOGRAM,LITER,ROLL,METER',
            'description' => 'nullable|max:255',
            'photo' => 'nullable|max:1000',
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
            if ($type == "create" && $request->photo !== NULL) { // create with photo
                $fileName = Str::random(6).'-'.$file->getClientOriginalName();
                $photo = $file->storeAs('assets/product',$fileName,'public');
                $messages = "Data Saved Successfully!";
            } else if ($type == "edit" && $request->photo !== NULL) { // create /edit with photo
                File::delete('storage/'. $productCheck->getRawOriginal('photo'));
                $fileName = Str::random(6).'-'.$file->getClientOriginalName();
                $photo = $file->storeAs('assets/product',$fileName,'public');
                $messages = "Data Saved Successfully!";
            } else if ($type == "create" && $request->photo == NULL) { // edit without photo
                $photo = NULL;
                $messages = "Data Saved Without Photo Successfully!";
            } else if ($type == "edit" && $request->photo == NULL) { // edit without photo
                $photo = $productCheck->getRawOriginal('photo');
                $messages = "Data Updated Without Photo Successfully!";
            }

            $category = Category::where('id', $request->category_id)->first();
            if ($request->product_code == "") {
                $productCode = 'PRD-'.Str::random(6);
            } else {
                $productCode = $request->product_code;
            }
            Product::updateOrCreate(['id' => $id],
                    [
                        'product_code' => $productCode,
                        'name' => $request->name,
                        'slug' => Str::slug($request->name),
                        'category_id' => $request->category_id,
                        'store_id' => $category->store_id,
                        'old_price' => $request->old_price,
                        'new_price' => $request->new_price,
                        'limit_stock' => $request->limit_stock,
                        'stock' => $request->stock,
                        'type' => $request->type,
                        'description' => $request->description,
                        'photo' => $photo,
                        'status' => $request->status,
                    ]); 
            return response()->json([
                'code' => 200,
                'icon' => "success",
                'notif' => "Success!",
                'messages' => $messages,
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
        File::delete('storage/'. $item->getRawOriginal('photo'));
        $item->delete();
        return response()->json(['code' => 1]);
    }

    public function deleteSelectedProduct(Request $request)
    {
        $id = $request->id;
        $items = Product::whereIn('id', $id)->get();
        foreach ($items as $key => $item) {
            File::delete('storage/'. $item->getRawOriginal('photo'));
            Product::whereIn('id', $id)->delete();
        }
        return response()->json(['code' => 1]);
    }

    public function printBarcode(Request $request)
    {
        $item = Product::where('id', $request->product_id)->first();
        $number = 1;
        for ($i=1; $i <= $request->quantity_barcode; $i++) { 
            $barcodeName[] = $item;
        }
        $pdf = Pdf::setOptions(['isRemoteEnabled' => TRUE, 'enable_javascript' => TRUE]);
        $pdf = Pdf::loadView('pages.admin.product.print_barcode', compact('number', 'barcodeName'));
        $pdf->setPaper('a4', 'protait'); 
        return $pdf->stream($item->name.".pdf");
    }

    public function productWhereCategory(Request $request, $id)
    {
        $items = Product::with('store', 'category')->where('category_id', decrypt($id))->get();
        $categories = DB::table('categories')->orderBy('name', 'ASC')->get();
        $category = DB::table('categories')->where('id', decrypt($id))->first();
        $title = "Data Products (Category : ". $category->name . ")";
        if($request->ajax()) {
            return datatables()->of($items)
                                ->addColumn('name', function($data) {
                                    return $data->product_code.' - '.$data->name;
                                })
                                ->addColumn('category', function($data){
                                    if ($data->category !== NULL) {
                                        return Str::upper($data->category->name);
                                    }
                                })
                                ->addColumn('price', function($data) {
                                    return '<del>'.number_format($data->old_price,0,",",".").'</del> | '.number_format($data->new_price,0,",",".").'';
                                })
                                ->addColumn('stock', function($data) {
                                    return '<span class="text-danger">'.$data->limit_stock.'</span> | <span class="text-success">'.$data->stock.'</span>';
                                })
                                ->addColumn('photo', function ($data) {
                                    if ($data->getRawOriginal('photo') !== NULL) {
                                        return '<a href="'.$data->photo.'" title="'.$data->photo.'" target="_blank><img src="'.$data->photo.'" alt="'.$data->photo.'" style="width: 100px; height: 100px;"><img src="'.$data->photo.'" alt="'.$data->photo.'" style="width: 100px; height: 100px;"></a>';    
                                    }
                                })
                                ->addColumn('status', function($data) {
                                    return $data->status;
                                })
                                ->addColumn('action', function($data) {
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Cetak Barcode" data-id="'.$data->id.'" data-original-title="Cetak Barcode" class="btn btn-success btn-md btn-barcode"><i class="fas fa-barcode"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    // $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['name', 'category', 'price', 'stock', 'photo', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.user.product.index_product_where_category', compact('title', 'categories', 'id'));
    }
}