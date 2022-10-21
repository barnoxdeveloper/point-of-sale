<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\{Hash, Validator, DB};

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = "Data User";
        $items = User::with('store')
                    ->whereNotIn('roles', ['ADMINISTRATOR'])
                    ->orderBy('roles', 'ASC')
                    ->get();
        $stores = DB::table('stores')->orderBy('name', 'ASC')->get();
        if($request->ajax()){
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="user_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('name', function($data){
                                    return $data->name;
                                })
                                ->addColumn('email', function($data){
                                     return '<a href="mailto:'.$data->email.'">'.$data->email.'</a>';
                                })
                                ->addColumn('roles', function($data){
                                    return $data->roles;
                                })
                                ->addColumn('store', function($data){
                                    if ($data->store !== NULL) {
                                        return Str::upper($data->store->name);
                                    }
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
                                ->rawColumns(['checkbox', 'name', 'email', 'roles', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.user.index_user', compact('title', 'stores'));
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
        $type = $request->type;
        $password = $request->password;
        // check user exists
        $userCheck = User::find($id);
        if ($userCheck == NULL && $password !== NULL || $userCheck !== NULL && $password !== NULL) {
            $pass = Hash::make($request->password);
        } else if($userCheck !== NULL && $password == NULL) {
            $pass = $userCheck->password;
        }

        if ($type == 'edit') {
            $validator = Validator::make( $request->all(),[
                'name' => 'required|max:50',
                'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore($userCheck->id)],
                'store_id' => 'nullable|exists:stores,id',
                'password' => 'max:50',
                'roles' => 'required|not_in:0|in:ADMINISTRATOR,MANAGER,CASHIER',
                'status' => 'required|in:ACTIVE,NON-ACTIVE',
            ]);
        } else if ($type == 'create') {
            $validator = Validator::make( $request->all(),[
                'name' => 'required|max:50',
                'email' => 'required|email|max:50|unique:users,email',
                'store_id' => 'nullable|exists:stores,id',
                'password' => 'max:50',
                'roles' => 'required|not_in:0|in:ADMINISTRATOR,MANAGER,CASHIER',
                'status' => 'required|in:ACTIVE,NON-ACTIVE',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'code' => 0,
                'notif' => "Error!",
                'messages' => $validator->errors(),
            ]);
        } else {
            User::updateOrCreate(['id' => $id],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => $pass,
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
        $item = User::where('id', $id)->firstOrFail();
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
        $item = User::find($id);
        $item->delete();
        return response()->json($item);
    }

    public function deleteSelectedUser(Request $request)
    {
        $id = $request->id;
        User::whereIn('id', $id)->delete();
        return response()->json(['code' => 1]);
    }
}
