<?php

namespace App\Http\Controllers;

use App\Models\{Store, User, Category};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::whereNotIn('roles', ['ADMINISTRATOR'])->count();
        $stores = Store::count();
        $categories = Category::count();
        return view('pages.dashboard', compact('users', 'stores', 'categories'));
    }
}
