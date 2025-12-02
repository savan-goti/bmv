<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('staff.dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ \App\Models\Setting::first()->favicon??asset('assets/img/no_img.jpg') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ \App\Models\Setting::first()->dark_logo??asset('assets/img/no_img.jpg') }}" alt="" class="w-100">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{route('staff.dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ \App\Models\Setting::first()->favicon??asset('assets/img/no_img.jpg') }}" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{ \App\Models\Setting::first()->light_logo??asset('assets/img/no_img.jpg') }}" alt="" class="w-100">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a @if(!in_array(request()->route()->getName(),['staff.dashboard'])) href="{{ route('staff.dashboard') }}" @endif class="nav-link menu-link @if(in_array(request()->route()->getName(),['staff.dashboard'])) active @endif">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Management</span></li>

                <li class="nav-item">
                    <a href="{{ route('staff.sellers.index') }}" class="nav-link menu-link @if(request()->routeIs('staff.sellers*')) active @endif">
                        <i class="ri-store-2-line"></i> <span data-key="t-sellers">Sellers</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
