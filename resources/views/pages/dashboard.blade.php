@extends('layouts.admin_layout')
@section('title', 'Dashboard')
@section('admin_content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @if(Auth::user()->is_roles_admin)
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $users }}</h3>
                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="{{ route('user.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $supplier }}</h3>
                                <p>Supplier</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-store"></i>
                            </div>
                            <a href="{{ route('supplier.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $stores }}</h3>
                                <p>Stores</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-store"></i>
                            </div>
                            <a href="{{ route('store.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $categories }}</h3>
                                <p>Category</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa fa-list"></i>
                            </div>
                            <a href="{{ route('category.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $products }}</h3>
                                <p>Products</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <a href="{{ route('product.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @elseif (Auth::user()->is_roles_employee)
                    <div class="col-lg-4 col-4">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $categories }}</h3>
                                <p>Category</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa fa-list"></i>
                            </div>
                            <a href="{{ route('category-user.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-4">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $products }}</h3>
                                <p>Products</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <a href="{{ route('product-user.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-4">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $users }}</h3>
                                <p>Orders</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <a href="{{ route('order-user.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    {{-- @elseif (Auth::user()->is_roles_cashier) --}}
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection
