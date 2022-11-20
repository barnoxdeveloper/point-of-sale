        
        
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light ml-4">Point of Sale</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ url('backend/dist/img/avatar5.png') }}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ route('dashboard') }}" class="d-block">{{ ucfirst(Auth::user()->name) }}</a>
                    </div>
                </div>
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <div class="dropdown-divider"></div>
                        @if(Auth::user()->is_roles_admin)
                        <li class="nav-item {{ (request()->is('dashboard')) ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item {{ (request()->is('user')) ? 'active' : '' }}">
                            <a href="{{ route('user.index') }}" class="nav-link {{ (request()->is('user')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item {{ (request()->is('store')) ? 'active' : '' }}">
                            <a href="{{ route('store.index') }}" class="nav-link {{ (request()->is('store')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-store"></i>
                                <p>Store</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item {{ (request()->is('category')) ? 'active' : '' }}">
                            <a href="{{ route('category.index') }}" class="nav-link {{ (request()->is('category')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-list"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item {{ (request()->is('product')) ? 'active' : '' }}">
                            <a href="{{ route('product.index') }}" class="nav-link {{ (request()->is('product*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>Product</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item {{ (request()->is('order')) ? 'active' : '' }}">
                            <a href="{{ route('order.index') }}" class="nav-link {{ (request()->is('order*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cash-register"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                        @elseif (Auth::user()->is_roles_manager)
                        <li class="nav-item {{ (request()->is('category-user')) ? 'active' : '' }}">
                            <a href="{{ route('category-user.index') }}" class="nav-link {{ (request()->is('category-user')) ? 'active' : '' }}">
                                <i class="nav-icon fa fa-list"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <div class="dropdown-divider"></div>
                        <li class="nav-item {{ (request()->is('product-user')) ? 'active' : '' }}">
                            <a href="{{ route('product-user.index') }}" class="nav-link {{ (request()->is('product-user*')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-boxes"></i>
                                <p>Product</p>
                            </a>
                        </li>
                        @elseif (Auth::user()->is_roles_cashier)
                        
                        @endif
                        <div class="dropdown-divider"></div>
                    </ul>
                </nav>
            </div>
        </aside>
        