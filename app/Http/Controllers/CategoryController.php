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
        $items = Category::orderBy('period', 'DESC')->get();
        $users = DB::table('users')->whereNotIn('roles', ['ADMINISTRATOR', 'HRD', 'FINANCE', 'DIRECTOR'])
                                    ->orderBy('name', 'ASC')
                                    ->get();
        // return $items;
        if($request->ajax()){
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="category_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('nip', function ($data) {
                                    return Str::upper($data->nip);
                                })
                                ->addColumn('name', function ($data) {
                                    if ($data->user == NULL) {
                                        return 'Not Found';
                                    } else {
                                        return Str::upper($data->user->name);
                                    }
                                })
                                ->addColumn('period', function ($data) {
                                    return Str::upper(date("M-Y", strtotime($data->period)));
                                })
                                ->addColumn('document', function ($data) {
                                    return '<a href="'.$data->document.'" title="'.$data->document.'" target="_blank">Document</a>';
                                })
                                ->addColumn('status', function($data){
                                    return $data->status;
                                })
                                ->addColumn('action', function($data){
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-warning btn-md editPost"><i class="bi bi-pencil-square"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="bi bi-trash3"></i></a>';
                                    return $button;
                                })
                                ->rawColumns(['checkbox', 'nip', 'name', 'period', 'document', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.category.index_category', compact('title', 'users'));
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
            'nip.*' => 'required|max:20|exists:users,nip|not_in:0',
            'period.*' => 'required|max:20',
            'document.*' => 'required|mimes:pdf|max:1000',
        ]);
        if ($validator->fails()) {
            return response()->json([
                    'code' => 0,
                    'notif' => "Error!",
                    'messages' => $validator->errors(),
                ]);    
        } else {
            if ($request->hasfile('document')) {
                $date = Carbon::now();
                foreach ($request->file('document') as $key => $item) {
                    $data[$key]['nip'] = $request->nip[$key];
                    $data[$key]['period'] = $request->period[$key];
                    $namaFile = $date.'-'.$request->nip[$key].'-'.$item->getClientOriginalName();
                    $data[$key]['document'] = $item->storeAs('assets/category',$namaFile,'public');
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
            'nip' => 'required|max:20',
            'period' => 'required|max:20',
            'document' => 'nullable|mimes:pdf|max:1000',
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
            $document = $request->file('document');
            //jika document tidak di rubah
            if($document == ""){
                $data['document'] = $item->getRawOriginal('document');
                $item->update($data);
                return response()->json([
                    'code' => 200,
                    'notif' => "Saved!",
                    'messages' => "Your Data has been Updated!",
                ]);
            }
            else if ($document !== ""){
                $data = $request->all();
                // jika document di rubah, maka unlink document yang lama
                File::delete('storage/'. $item->getRawOriginal('document'));
                $date = Carbon::now();
                $fileName = $date.'-'.$document->getClientOriginalName();
                $data['document'] = $document->storeAs('assets/category',$fileName,'public');
                $item->update($data);
                return response()->json([
                    'code' => 200,
                    'notif' => "Updated!",
                    'messages' => "Your Data has been Updated!",
                ]);
            }
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
        File::delete('storage/'. $item->getRawOriginal('document'));
        $item->delete();
        return response()->json(['code' => 1]);
    }

    public function deleteSelectedCategory(Request $request)
    {
        $id = $request->id;
        $items = Category::whereIn('id', $id)->get();
        foreach ($items as $key => $item) {
            File::delete('storage/'. $item->getRawOriginal('document'));
            Category::whereIn('id', $id)->delete();
        }
        return response()->json(['code' => 1]);
    }
}
