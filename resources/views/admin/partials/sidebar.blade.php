<nav id="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <div class="avatar">
            <img src="{{ asset('admin/img/avatar-6.jpg') }}" alt="..." class="img-fluid rounded-circle">
        </div>
        <div class="title">
            <h1 class="h5 mb-1">{{ auth()->user()->name ?? 'Admin' }}</h1>
            <p class="mb-0">Administrator</p>
        </div>
    </div>

    <span class="heading">Main</span>
    <ul class="list-unstyled">
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}"> <i class="icon-home"></i>Dashboard</a>
        </li>
        <li class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.index') }}"> <i class="icon-user-1"></i>Customers</a>
        </li>
        <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}"> <i class="icon-new-file"></i>Orders</a>
        </li>
        <li class="{{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
            <a href="{{ route('admin.inventory.index') }}"> <i class="icon-grid"></i>Inventory</a>
        </li>
        <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.index') }}"> <i class="fa fa-bar-chart"></i>Reports</a>
        </li>
    </ul>

    <span class="heading">Catalog</span>
    <ul class="list-unstyled">
        <li class="{{ request()->routeIs('admin.watches.*') ? 'active' : '' }}"><a href="{{ route('admin.watches.index') }}"> <i class="icon-padnote"></i>Watches</a></li>
        <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"><a href="{{ route('admin.brands.index') }}"> <i class="icon-windows"></i>Brands</a></li>
        <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}"><a href="{{ route('admin.suppliers.index') }}"> <i class="icon-settings"></i>Suppliers</a></li>
        <li class="{{ request()->routeIs('admin.security') ? 'active' : '' }}">
            <a href="{{ route('admin.security') }}">
                <i class="fa fa-lock"></i> Security
            </a>
        </li>
    </ul>
</nav>
