<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator, File};

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data Category";
        $items = Category::with('store')->get();
        $stores = DB::table('stores')->orderBy('name', 'ASC')->get();
        if($request->ajax()) {
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="category_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('name', function ($data) {
                                    return $data->name;
                                })
                                ->addColumn('storeName', function ($data) {
                                    if ($data->store !== NULL) {
                                        return $data->store->store_code.' | '.$data->store->name;
                                    }
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
                                    $url = route('product-where-category', encrypt($data->id));
                                    $button = '<a href="'.$url.'" title="Data Product" class="btn btn-primary btn-md"><i class="fas fa-boxes"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" class="btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Deleted" data-id="'.$data->id.'" class="btn btn-danger btn-md delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'name', 'storeName', 'photo', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.category.index_category', compact('title', 'stores'));
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
        $validator = Validator::make( $request->all(),[
            'store_id.*' => 'required|exists:stores,id|not_in:0',
            'name.*' => 'required|max:255',
            'photo.*' => 'required|mimes:png,jpg,jpeg,svg|max:1000',
        ]);
        // return $request->file('photo');
        if ($validator->fails()) {
            return response()->json([
                    'code' => 0,
                    'notif' => "Error!",
                    'messages' => $validator->errors(),
                ]);    
        } else {
            if ($request->hasfile('photo')) {
                $slug = str_replace(' ', '-', array_map('strtolower', $request->name));
                foreach ($request->file('photo') as $key => $item) {
                    $data[$key]['store_id'] = $request->store_id[$key];
                    $data[$key]['name'] = $request->name[$key];
                    $data[$key]['slug'] = $slug[$key];
                    $fileName = Str::random(6).'-'.$item->getClientOriginalName();
                    $data[$key]['photo'] = $item->storeAs('assets/category',$fileName,'public');
                    $data[$key]['status'] = "NON-ACTIVE";
                    $data[$key]['created_at'] = Carbon::now();
                    $data[$key]['updated_at'] = Carbon::now();
                }
            }
            Category::insert($data);
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
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Category::where('id', $id)->firstOrFail();
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Category::findOrFail($id);
        $validator = Validator::make( $request->all(),[
            'store_id' => 'required|exists:stores,id|not_in:0',
            'name' => 'required|max:255',
            'photo' => 'nullable|mimes:png,jpg,jpeg,svg|max:1000',
            'status' => 'required|in:ACTIVE,NON-ACTIVE',
        ]);
        if ($validator->fails()) {
            return response()->json([
                    'code' => 0,
                    'notif' => "Error!",
                    'messages' => $validator->errors(),
                ]);    
        } else {
            $data = $request->all();
            $photo = $request->file('photo');
            //jika photo tidak di rubah
            if($photo == ""){
                $data['photo'] = $item->getRawOriginal('photo');
                $item->update($data);
            }
            else if ($photo !== ""){
                $data = $request->all();
                // jika photo di rubah, maka unlink photo yang lama
                File::delete('storage/'. $item->getRawOriginal('photo'));
                $date = Carbon::now();
                $fileName = Str::random(6).'-'.$photo->getClientOriginalName();
                $data['photo'] = $photo->storeAs('assets/category',$fileName,'public');
                $item->update($data);
            }
            return response()->json([
                'code' => 200,
                'notif' => "Updated!",
                'messages' => "Your Data has been Updated!",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Category::find($id);
        File::delete('storage/'. $item->getRawOriginal('photo'));
        $item->delete();
        return response()->json(['code' => 1]);
    }

    public function deleteSelectedCategory(Request $request)
    {
        $id = $request->id;
        $items = Category::whereIn('id', $id)->get();
        foreach ($items as $key => $item) {
            File::delete('storage/'. $item->getRawOriginal('photo'));
            Category::whereIn('id', $id)->delete();
        }
        return response()->json(['code' => 1]);
    }

    public function categoryWhereStore(Request $request, $id)
    {
        $items = Category::where('store_id', decrypt($id))->get();
        $store = DB::table('stores')->where('id', decrypt($id))->first();
        $title = "Data Category (Store : ". $store->name . ")";
        if($request->ajax()) {
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="category_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('name', function ($data) {
                                    return $data->name;
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
                                    $url = route('product-where-category', encrypt($data->id));
                                    $button = '<a href="'.$url.'" title="Data Product" class="btn btn-primary btn-md"><i class="fas fa-boxes"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="far fa-edit"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="far fa-trash-alt"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'name', 'photo', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.category.category_where_store', compact('id', 'title'));
    }
}
