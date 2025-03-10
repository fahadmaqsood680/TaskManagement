<?php 
$currentRouteName = Route::currentRouteName();  // Get the current route name
$admin = auth()->user();
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('admin/dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light"> Task Management System  </span>
    </a>

    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="{{ url('admin/dashboard') }}" class="d-block">
                    {{ $admin->name ?? 'Admin' }}  
                    <br>
                    <small class="text-muted">{{ ucfirst($admin->role ?? 'Admin') }}</small> 
                </a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ $currentRouteName === 'admin.dashboard' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if($admin->role === 'admin' || $admin->role === 'manager')
                <!-- Categories -->
                <li class="nav-item {{ $currentRouteName === 'categories.index' || $currentRouteName === 'categories.create' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Categories <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ $currentRouteName === 'categories.index' ? 'active' : '' }}">
                                <i class="fas fa-th-list nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.create') }}" class="nav-link {{ $currentRouteName === 'categories.create' ? 'active' : '' }}">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Add Category</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Tasks -->
                <li class="nav-item {{ $currentRouteName === 'tasks.index' || $currentRouteName === 'tasks.create' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Tasks <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tasks.index') }}" class="nav-link {{ $currentRouteName === 'tasks.index' ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list nav-icon"></i>
                                <p>Tasks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tasks.create') }}" class="nav-link {{ $currentRouteName === 'tasks.create' ? 'active' : '' }}">
                                <i class="fas fa-plus-circle nav-icon"></i>
                                <p>Add Task</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Users -->
                <li class="nav-item {{ $currentRouteName === 'users.index' || $currentRouteName === 'users.create' ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ $currentRouteName === 'users.index' ? 'active' : '' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.create') }}" class="nav-link {{ $currentRouteName === 'users.create' ? 'active' : '' }}">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Add User</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Logout -->
                <li class="nav-item">
                    <a href="{{ url('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
