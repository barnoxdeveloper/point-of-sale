<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Validator, File};

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = Category::where('store_id', Auth::user()->store_id)
                            ->where('status', 'ACTIVE')
                            ->get();
        if ($category) {
            return ResponseFormatter::success(
                $category,
                'Data Category Berhasil diambil'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data Category tidak ada',
                404
            );
        }
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
        try {
            $validator = Validator::make( $request->all(),[
                'name' => 'required|max:255',
                'status' => 'nullable|in:ACTIVE,NON-ACTIVE',
                'photo' => 'required|mimes:png,jpg,jpeg,svg|max:1000',
            ]);
            if ($validator->fails()) {
                return ResponseFormatter::error(
                    ['error' => $validator->errors()], 
                    'Upload Photo Fails', 
                    401
                );    
            }

            $file = $request->file('photo');
            if ($request->file('photo')) {
                $fileName = Str::random(6).'-'.$file->getClientOriginalName();
                $photo = $file->storeAs('assets/category',$fileName,'public');
            }

            $category = Category::create([
                'name' => $request->name,
                'status' => $request->status,
                'store_id' => Auth::user()->store_id,
                'slug' => Str::slug($request->name),
                'photo' => $photo,
            ]);

            if ($category) {
                return ResponseFormatter::success(
                    $category,
                    'Data Berhasil dibuat'
                );
            }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went Wrong!',
                'error' => $error
            ], 'Authentication Failed', 500);
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
        try {
            // return $request->all();
            // $id = $request->id;
            $validator = Validator::make( $request->all(),[
                'name' => 'required|max:255',
                'photo' => 'nullable|mimes:png,jpg,jpeg,svg|max:1000',
                'status' => 'nullable|in:ACTIVE,NON-ACTIVE',
            ]);
            if ($validator->fails()) {
                return ResponseFormatter::error(
                    ['error' => $validator->errors()], 
                    'Upload Photo Fails!', 
                    401
                );    
            }
            $item = Category::findOrFail($id);
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $file = $request->file('photo');
            if($file == "") {
                $data['photo'] = $item->getRawOriginal('photo');
            } else if ($file !== "") {
                File::delete('storage/'. $item->getRawOriginal('photo'));
                $fileName = Str::random(6).'-'.$file->getClientOriginalName();
                $data['photo'] = $file->storeAs('assets/category',$fileName,'public');
            }
            $item->update($data);
            if ($item) {
                return ResponseFormatter::success(
                    $item,
                    'Data Berhasil diupdate!'
                );
            }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went Wrong!',
                'error' => $error
            ], 'Authentication Failed', 500);
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
        try {
            $item = Category::find($id);
            File::delete('storage/'. $item->getRawOriginal('photo'));
            $item->delete();
            if ($item) {
                return ResponseFormatter::success(
                    $item,
                    'Data Berhasil dihapus!'
                );
            }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went Wrong!',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }
}
