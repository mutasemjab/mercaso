<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Vertex</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->


                @if (auth()->user()->is_super_admin == true)

                    @if ($user->can('shop-table') || $user->can('shop-add') || $user->can('shop-edit') || $user->can('shop-delete'))
                        <li class="nav-item">
                            <a href="{{ route('shops.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.shops') }} </p>
                            </a>
                        </li>
                    @endif

                @else

                @if (
                    $user->can('banner-table') ||
                        $user->can('banner-add') ||
                        $user->can('banner-edit') ||
                        $user->can('banner-delete'))
                    <li class="nav-item">
                        <a href="{{ route('banners.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> {{ __('messages.banners') }} </p>
                        </a>
                    </li>
                @endif

              @if (
                    $user->can('businessType-table') ||
                        $user->can('businessType-add') ||
                        $user->can('businessType-edit') ||
                        $user->can('businessType-delete'))
                    <li class="nav-item">
                        <a href="{{ route('businessTypes.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> {{ __('messages.businessTypes') }} </p>
                        </a>
                    </li>
                @endif

                    @if (
                        $user->can('customer-table') ||
                            $user->can('customer-add') ||
                            $user->can('customer-edit') ||
                            $user->can('customer-delete'))
                        <li class="nav-item">
                            <a href="{{ route('admin.customer.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.Customers') }} </p>
                            </a>
                        </li>
                    @endif


                    @if (
                        $user->can('wholeSale-table') ||
                            $user->can('wholeSale-add') ||
                            $user->can('wholeSale-edit') ||
                            $user->can('wholeSale-delete'))
                        <li class="nav-item">
                            <a href="{{ route('admin.wholeSale.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.wholeSales') }} </p>
                            </a>
                        </li>
                    @endif


                    @if (
                        $user->can('category-table') ||
                            $user->can('category-add') ||
                            $user->can('category-edit') ||
                            $user->can('category-delete'))
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.categories') }} </p>
                            </a>
                        </li>
                    @endif

                    @if ($user->can('unit-table') || $user->can('unit-add') || $user->can('unit-edit') || $user->can('unit-delete'))
                        <li class="nav-item">
                            <a href="{{ route('units.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.units') }} </p>
                            </a>
                        </li>
                    @endif

                    @if ($user->can('brand-table') || $user->can('brand-add') || $user->can('brand-edit') || $user->can('brand-delete'))
                        <li class="nav-item">
                            <a href="{{ route('brands.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.brands') }} </p>
                            </a>
                        </li>
                    @endif
                    @if (
                        $user->can('product-table') ||
                            $user->can('product-add') ||
                            $user->can('product-edit') ||
                            $user->can('product-delete'))
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.products') }} </p>
                            </a>
                        </li>
                    @endif
                    @if ($user->can('offer-table') || $user->can('offer-add') || $user->can('offer-edit') || $user->can('offer-delete'))
                        <li class="nav-item">
                            <a href="{{ route('offers.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.offers') }} </p>
                            </a>
                        </li>
                    @endif
                    @if ($user->can('coupon-table') || $user->can('coupon-add') || $user->can('coupon-edit') || $user->can('coupon-delete'))
                        <li class="nav-item">
                            <a href="{{ route('coupons.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.coupons') }} </p>
                            </a>
                        </li>
                    @endif

                    @if (
                        $user->can('noteVoucherType-table') ||
                            $user->can('noteVoucherType-add') ||
                            $user->can('noteVoucherType-edit') ||
                            $user->can('noteVoucherType-delete'))
                        <li class="nav-item">
                            <a href="{{ route('noteVoucherTypes.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.noteVoucherTypes') }} </p>
                            </a>
                        </li>
                    @endif
                    @php
                        $noteVouchertypes = App\Models\NoteVoucherType::get();
                        $locale = app()->getLocale();
                    @endphp
                    @foreach ($noteVouchertypes as $noteVouchertype)
                        <li class="nav-item">
                            <a href="{{ route('noteVouchers.create', ['id' => $noteVouchertype->id]) }}"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{$locale == 'ar'? $noteVouchertype->name : $noteVouchertype->name_en }} </p>
                            </a>
                        </li>
                    @endforeach

                    @if (
                        $user->can('noteVoucher-table') ||
                            $user->can('noteVoucher-add') ||
                            $user->can('noteVoucher-edit') ||
                            $user->can('noteVoucher-delete'))
                        <li class="nav-item">
                            <a href="{{ route('noteVouchers.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.noteVouchers') }} </p>
                            </a>
                        </li>
                    @endif







                    @if ($user->can('order-table') || $user->can('order-add') || $user->can('order-edit') || $user->can('order-delete'))
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.Orders') }} </p>
                            </a>
                        </li>
                    @endif



                    @if (
                        $user->can('delivery-table') ||
                            $user->can('delivery-add') ||
                            $user->can('delivery-edit') ||
                            $user->can('delivery-delete'))
                        <li class="nav-item">
                            <a href="{{ route('deliveries.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.deliveries') }} </p>
                            </a>
                        </li>
                    @endif

                    @if (
                        $user->can('warehouse-table') ||
                            $user->can('warehouse-add') ||
                            $user->can('warehouse-edit') ||
                            $user->can('warehouse-delete'))
                        <li class="nav-item">
                            <a href="{{ route('warehouses.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.warehouses') }} </p>
                            </a>
                        </li>
                    @endif


                    @if (
                        $user->can('notification-table') ||
                            $user->can('notification-add') ||
                            $user->can('notification-edit') ||
                            $user->can('notification-delete'))
                        <li class="nav-item">
                            <a href="{{ route('notifications.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.notifications') }} </p>
                            </a>
                        </li>
                    @endif
           
                    @if (
                        $user->can('point-transaction-table') ||
                            $user->can('point-transaction-add') ||
                            $user->can('point-transaction-edit') ||
                            $user->can('point-transaction-delete'))
                        <li class="nav-item">
                            <a href="{{ route('point-transactions.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> {{ __('messages.Point_Transactions') }} </p>
                            </a>
                        </li>
                    @endif




                    <li class="nav-item">
                        <a href="{{ route('admin.setting.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{__('messages.Settings')}} </p>
                        </a>
                    </li>
                    @if (auth()->user()->is_super == true)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                {{ __('messages.reports') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                           
                            <li class="nav-item">
                                <a href="{{ route('order_report') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> {{ __('messages.order_report') }} </p>
                                </a>
                            </li>
                        
                            <li class="nav-item">
                                <a href="{{ route('user_report') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> {{ __('messages.user_report') }} </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('product_report') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> {{ __('messages.product_report') }} </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif

                     @if (
                    $user->can('page-table') ||
                        $user->can('page-add') ||
                        $user->can('page-edit') ||
                        $user->can('page-delete'))
                    <li class="nav-item">
                        <a href="{{ route('pages.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{__('messages.Pages')}} </p>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('taxes.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('messages.taxes') }} </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('crvs.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('messages.crvs') }} </p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.login.edit', auth()->user()->id) }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('messages.Admin_account') }} </p>
                        </a>
                    </li>

                    @if ($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') || $user->can('role-delete'))
                        <li class="nav-item">
                            <a href="{{ route('admin.role.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <span>{{ __('messages.Roles') }} </span>
                            </a>
                        </li>
                    @endif

                    @if (
                        $user->can('employee-table') ||
                            $user->can('employee-add') ||
                            $user->can('employee-edit') ||
                            $user->can('employee-delete'))
                        <li class="nav-item">
                            <a href="{{ route('admin.employee.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <span> {{ __('messages.Employee') }} </span>
                            </a>
                        </li>
                    @endif


                @endif


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
