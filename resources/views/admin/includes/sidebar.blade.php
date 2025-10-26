<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">California Cash&Carry</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('messages.dashboard') }}</p>
                    </a>
                </li>

                <!-- User Management -->
                @if(auth()->user()->can('role-table') || auth()->user()->can('employee-table'))
                    <li class="nav-item has-treeview {{ request()->routeIs('admin.role.*') || request()->routeIs('admin.employee.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.role.*') || request()->routeIs('admin.employee.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                {{ __('messages.user_management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->can('role-table'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.role.index') }}" class="nav-link {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.roles') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->can('employee-table'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.employee.index') }}" class="nav-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.employees') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Customer Management -->
                @if(auth()->user()->can('customer-table'))
                    <li class="nav-item">
                        <a href="{{ route('admin.customer.index') }}" class="nav-link {{ request()->routeIs('admin.customer.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('messages.customers') }}</p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->can('wholeSale-table'))
                    <li class="nav-item">
                        <a href="{{ route('admin.wholeSale.index') }}" class="nav-link {{ request()->routeIs('admin.wholeSale.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>{{ __('messages.wholesale') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Product Management -->
                @if(auth()->user()->can('category-table') || auth()->user()->can('unit-table') || auth()->user()->can('product-table') || auth()->user()->can('brand-table'))
                    <li class="nav-item has-treeview {{ request()->routeIs('categories.*') || request()->routeIs('units.*') || request()->routeIs('products.*') || request()->routeIs('brands.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('categories.*') || request()->routeIs('units.*') || request()->routeIs('products.*') || request()->routeIs('brands.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                {{ __('messages.product_management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->can('category-table'))
                                <li class="nav-item">
                                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.categories') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->can('unit-table'))
                                <li class="nav-item">
                                    <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.units') }}</p>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->can('brand-table'))
                                <li class="nav-item">
                                    <a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.brands') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->can('product-table'))
                                <li class="nav-item">
                                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.products') }}</p>
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->can('product-table'))
                                <li class="nav-item">
                                    <a href="{{ route('point-products.index') }}" class="nav-link {{ request()->routeIs('point-products.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Products buy by points</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Orders & Sales -->
                @if(auth()->user()->can('order-table'))
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>{{ __('messages.orders') }}</p>
                        </a>
                    </li>
                @endif
            
                @if(auth()->user()->can('order-table'))
                    <li class="nav-item">
                        <a href="{{ route('pointProducts.purchases') }}" class="nav-link {{ request()->routeIs('pointProducts.purchases.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Orders For Product Points</p>
                        </a>
                    </li>
                @endif

                <!-- Marketing -->
                @if(auth()->user()->can('banner-table') || auth()->user()->can('offer-table') || auth()->user()->can('coupon-table'))
                    <li class="nav-item has-treeview {{ request()->routeIs('banners.*') || request()->routeIs('offers.*') || request()->routeIs('coupons.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('banners.*') || request()->routeIs('offers.*') || request()->routeIs('coupons.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>
                                {{ __('messages.marketing') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->can('banner-table'))
                                <li class="nav-item">
                                    <a href="{{ route('banners.index') }}" class="nav-link {{ request()->routeIs('banners.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.banners') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->can('offer-table'))
                                <li class="nav-item">
                                    <a href="{{ route('offers.index') }}" class="nav-link {{ request()->routeIs('offers.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.offers') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->can('coupon-table'))
                                <li class="nav-item">
                                    <a href="{{ route('coupons.index') }}" class="nav-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.coupons') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Inventory & Warehouse -->
                @if(auth()->user()->can('warehouse-table'))
                    <li class="nav-item">
                        <a href="{{ route('warehouses.index') }}" class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>{{ __('messages.warehouses') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Note Vouchers -->
                @if(auth()->user()->can('noteVoucherType-table') || auth()->user()->can('noteVoucher-table'))
                    <li class="nav-item has-treeview {{ request()->routeIs('noteVoucherTypes.*') || request()->routeIs('noteVouchers.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('noteVoucherTypes.*') || request()->routeIs('noteVouchers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                {{ __('messages.note_vouchers') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->can('noteVoucherType-table'))
                                <li class="nav-item">
                                    <a href="{{ route('noteVoucherTypes.index') }}" class="nav-link {{ request()->routeIs('noteVoucherTypes.*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.note_voucher_types') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @php
                                $noteVouchertypes = App\Models\NoteVoucherType::get();
                                $locale = app()->getLocale();
                            @endphp
                            @foreach ($noteVouchertypes as $noteVouchertype)
                                <li class="nav-item">
                                    <a href="{{ route('noteVouchers.create', ['id' => $noteVouchertype->id]) }}" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ $locale == 'ar' ? $noteVouchertype->name : $noteVouchertype->name_en }}</p>
                                    </a>
                                </li>
                            @endforeach
                            
                            @if(auth()->user()->can('noteVoucher-table'))
                                <li class="nav-item">
                                    <a href="{{ route('noteVouchers.index') }}" class="nav-link {{ request()->routeIs('noteVouchers.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.all_note_vouchers') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Delivery -->
                @if(auth()->user()->can('delivery-table'))
                    <li class="nav-item">
                        <a href="{{ route('deliveries.index') }}" class="nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>{{ __('messages.delivery') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Points -->
                @if(auth()->user()->can('point-table'))
                    <li class="nav-item">
                        <a href="{{ route('point-transactions.index') }}" class="nav-link {{ request()->routeIs('point-transactions.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-coins"></i>
                            <p>{{ __('messages.point_transactions') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Notifications -->
                @if(auth()->user()->can('notification-table'))
                    <li class="nav-item">
                        <a href="{{ route('notifications.create') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>{{ __('messages.notifications') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Business Types (if exists) -->
                @if(isset($user) && method_exists($user, 'can') && $user->can('businessType-table'))
                    <li class="nav-item">
                        <a href="{{ route('businessTypes.index') }}" class="nav-link {{ request()->routeIs('businessTypes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>{{ __('messages.business_types') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Pages -->
                @if(auth()->user()->can('page-table'))
                    <li class="nav-item">
                        <a href="{{ route('pages.index') }}" class="nav-link {{ request()->routeIs('pages.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('messages.pages') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Taxes -->
                <li class="nav-item">
                    <a href="{{ route('taxes.index') }}" class="nav-link {{ request()->routeIs('taxes.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-percentage"></i>
                        <p>{{ __('messages.taxes') }}</p>
                    </a>
                </li>

                <!-- CRVs -->
                <li class="nav-item">
                    <a href="{{ route('crvs.index') }}" class="nav-link {{ request()->routeIs('crvs.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>{{ __('messages.crvs') }}</p>
                    </a>
                </li>

                <!-- Reports -->
                @if(auth()->user()->can('order-report') || auth()->user()->can('products-report') || auth()->user()->can('users-report') || auth()->user()->is_super_admin)
                    <li class="nav-item has-treeview {{ request()->routeIs('*_report') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('*_report') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                {{ __('messages.reports') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(auth()->user()->can('order-report') || auth()->user()->is_super_admin)
                                <li class="nav-item">
                                    <a href="{{ route('admin.tax-crv.index') }}" class="nav-link {{ request()->routeIs('admin.tax-crv.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tax & Crv Reports</p>
                                    </a>
                                </li>
                            @endif
                          
                            @if(auth()->user()->can('order-report') || auth()->user()->is_super_admin)
                                <li class="nav-item">
                                    <a href="{{ route('order_report') }}" class="nav-link {{ request()->routeIs('order_report') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.order_reports') }}</p>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->can('users-report') || auth()->user()->is_super_admin)
                                <li class="nav-item">
                                    <a href="{{ route('user_report') }}" class="nav-link {{ request()->routeIs('user_report') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.user_reports') }}</p>
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->can('products-report') || auth()->user()->is_super_admin)
                                <li class="nav-item">
                                    <a href="{{ route('product_report') }}" class="nav-link {{ request()->routeIs('product_report') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('messages.product_reports') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                <!-- Settings -->
                @if(auth()->user()->can('setting-table'))
                    <li class="nav-item">
                        <a href="{{ route('admin.setting.index') }}" class="nav-link {{ request()->routeIs('admin.setting.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>{{ __('messages.settings') }}</p>
                        </a>
                    </li>
                @endif

                <!-- Admin Account -->
                <li class="nav-item">
                    <a href="{{ route('admin.login.edit', auth()->user()->id) }}" class="nav-link {{ request()->routeIs('admin.login.edit') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p>{{ __('messages.admin_account') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>