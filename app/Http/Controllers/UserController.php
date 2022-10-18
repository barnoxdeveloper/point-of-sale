<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $items = User::whereNotIn('roles', ['ADMINISTRATOR'])
                        ->orderBy('name', 'ASC')
                        ->get();
        // return $items;
        if($request->ajax()){
            return datatables()->of($items)
                                ->addColumn('checkbox', function($data) {
                                    return '<input type="checkbox" name="user_checkbox" data-id="'.$data['id'].'"><label></label>';
                                })
                                ->addColumn('name', function($data){
                                    return $data->name;
                                })
                                ->addColumn('email', function($data){
                                    return $data->email;
                                })
                                ->addColumn('roles', function($data){
                                    return $data->roles;
                                })
                                ->addColumn('status', function($data){
                                    return $data->status;
                                })
                                ->addColumn('action', function($data){
                                    $button = '<a href="javascript:void(0)" data-toggle="tooltip" title="Edit" data-id="'.$data->id.'" data-original-title="Edit" class="edit btn btn-warning btn-md editPost"><i class="bi bi-pencil-square"></i></a>';
                                    $button .= '&nbsp;&nbsp;';
                                    $button .= '<a href="#" title="Deleted" class="btn btn-danger delete" data-id="'.$data->id.'" data-toggle="modal" data-target="#delete"><i class="bi bi-trash3"></i></a>';
                                    return $button;
                                    
                                })
                                ->rawColumns(['checkbox', 'name', 'email', 'roles', 'status', 'action'])
                                ->addIndexColumn()
                                ->make(true);
        }
        return view('pages.admin.user.index_user', compact('title'));
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
        //
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
        //
    }
}
