<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{route('owner.dashboard')}}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ \App\Models\Setting::first()->favicon??asset('assets/img/no_img.jpg') }}" alt="" height="30">
            </span>
            @if(\App\Models\Setting::first()->dark_logo)
                <span class="logo-lg">
                    <img src="{{ \App\Models\Setting::first()->dark_logo }}" alt="" class="w-100">
                </span>
            @endif
        </a>
        <!-- Light Logo-->
        <a href="{{route('owner.dashboard')}}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ \App\Models\Setting::first()->favicon??asset('assets/img/no_img.jpg') }}" alt="" height="30">
            </span>
            @if(\App\Models\Setting::first()->light_logo)
                <span class="logo-lg">
                    <img src="{{ \App\Models\Setting::first()->light_logo }}" alt="" class="w-100">
                </span>
            @endif
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
                    <a @if(!in_array(request()->route()->getName(),['owner.dashboard'])) href="{{ route('owner.dashboard') }}" @endif class="nav-link menu-link @if(in_array(request()->route()->getName(),['owner.dashboard'])) active @endif">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Organization</span></li>

                <li class="nav-item">
                    <a href="{{ route('owner.job-positions.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.job-positions.*')) active @endif">
                        <i class="ri-briefcase-line"></i> <span data-key="t-job-positions">Job Positions</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.branches.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.branches.*')) active @endif">
                        <i class="ri-building-line"></i> <span data-key="t-branches">Branches</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.branch-positions.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.branch-positions.*')) active @endif">
                        <i class="ri-user-location-line"></i> <span data-key="t-branch-positions">Branch Positions</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Management</span></li>

                <li class="nav-item">
                    <a href="{{ route('owner.admins.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.admins.*')) active @endif">
                        <i class="ri-user-settings-line"></i> <span data-key="t-admins">Admins</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.staffs.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.staffs.*')) active @endif">
                        <i class="ri-team-line"></i> <span data-key="t-staffs">Staff</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.sellers.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.sellers.*')) active @endif">
                        <i class="ri-store-2-line"></i> <span data-key="t-sellers">Sellers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.customers.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.customers.*')) active @endif">
                        <i class="ri-user-line"></i> <span data-key="t-customers">Customers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.support-team.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.support-team.*')) active @endif">
                        <i class="ri-customer-service-2-line"></i> <span data-key="t-support-team">Support Team</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Product Management</span></li>

                <li class="nav-item">
                    <a href="{{ route('owner.categories.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.categories.*')) active @endif">
                        <i class="ri-folder-line"></i> <span data-key="t-categories">Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.sub-categories.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.sub-categories.*')) active @endif">
                        <i class="ri-folder-open-line"></i> <span data-key="t-sub-categories">Sub Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.child-categories.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.child-categories.*')) active @endif">
                        <i class="ri-folder-2-line"></i> <span data-key="t-child-categories">Child Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.brands.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.brands.*')) active @endif">
                        <i class="ri-award-line"></i> <span data-key="t-brands">Brands</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.collections.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.collections.*')) active @endif">
                        <i class="ri-stack-line"></i> <span data-key="t-collections">Collections</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('owner.products.index') }}" class="nav-link menu-link @if(request()->routeIs('owner.products.*')) active @endif">
                        <i class="ri-shopping-bag-3-line"></i> <span data-key="t-products">Products</span>
                    </a>
                </li>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Master Data</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMasterData" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMasterData">
                        <i class="ri-database-2-line"></i> <span data-key="t-master-data">Product Master</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('owner.master.*') ? 'show' : '' }}" id="sidebarMasterData">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('owner.master.units.index') }}" class="nav-link {{ request()->routeIs('owner.master.units.*') ? 'active' : '' }}">Units</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('owner.master.hsn-sacs.index') }}" class="nav-link {{ request()->routeIs('owner.master.hsn-sacs.*') ? 'active' : '' }}">HSN/SAC</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('owner.master.colors.index') }}" class="nav-link {{ request()->routeIs('owner.master.colors.*') ? 'active' : '' }}">Colors</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('owner.master.sizes.index') }}" class="nav-link {{ request()->routeIs('owner.master.sizes.*') ? 'active' : '' }}">Sizes</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('owner.master.suppliers.index') }}" class="nav-link {{ request()->routeIs('owner.master.suppliers.*') ? 'active' : '' }}">Suppliers</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
