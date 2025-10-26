<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
        <img src="<?php echo e(asset('assets/admin/dist/img/AdminLTELogo.png')); ?>" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">California Cash&Carry</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('assets/admin/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo e(auth()->user()->name); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p><?php echo e(__('messages.dashboard')); ?></p>
                    </a>
                </li>

                <!-- User Management -->
                <?php if(auth()->user()->can('role-table') || auth()->user()->can('employee-table')): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('admin.role.*') || request()->routeIs('admin.employee.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link <?php echo e(request()->routeIs('admin.role.*') || request()->routeIs('admin.employee.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                <?php echo e(__('messages.user_management')); ?>

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(auth()->user()->can('role-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('admin.role.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.role.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.roles')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('employee-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('admin.employee.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.employee.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.employees')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Customer Management -->
                <?php if(auth()->user()->can('customer-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.customer.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.customer.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p><?php echo e(__('messages.customers')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(auth()->user()->can('wholeSale-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.wholeSale.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.wholeSale.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p><?php echo e(__('messages.wholesale')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Product Management -->
                <?php if(auth()->user()->can('category-table') || auth()->user()->can('unit-table') || auth()->user()->can('product-table') || auth()->user()->can('brand-table')): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('categories.*') || request()->routeIs('units.*') || request()->routeIs('products.*') || request()->routeIs('brands.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link <?php echo e(request()->routeIs('categories.*') || request()->routeIs('units.*') || request()->routeIs('products.*') || request()->routeIs('brands.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                <?php echo e(__('messages.product_management')); ?>

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(auth()->user()->can('category-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('categories.index')); ?>" class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.categories')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('unit-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('units.index')); ?>" class="nav-link <?php echo e(request()->routeIs('units.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.units')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(auth()->user()->can('brand-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('brands.index')); ?>" class="nav-link <?php echo e(request()->routeIs('brands.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.brands')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('product-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.products')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(auth()->user()->can('product-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('point-products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('point-products.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Products buy by points</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Orders & Sales -->
                <?php if(auth()->user()->can('order-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('orders.index')); ?>" class="nav-link <?php echo e(request()->routeIs('orders.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p><?php echo e(__('messages.orders')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>
            
                <?php if(auth()->user()->can('order-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('pointProducts.purchases')); ?>" class="nav-link <?php echo e(request()->routeIs('pointProducts.purchases.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Orders For Product Points</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Marketing -->
                <?php if(auth()->user()->can('banner-table') || auth()->user()->can('offer-table') || auth()->user()->can('coupon-table')): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('banners.*') || request()->routeIs('offers.*') || request()->routeIs('coupons.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link <?php echo e(request()->routeIs('banners.*') || request()->routeIs('offers.*') || request()->routeIs('coupons.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-bullhorn"></i>
                            <p>
                                <?php echo e(__('messages.marketing')); ?>

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(auth()->user()->can('banner-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('banners.index')); ?>" class="nav-link <?php echo e(request()->routeIs('banners.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.banners')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('offer-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('offers.index')); ?>" class="nav-link <?php echo e(request()->routeIs('offers.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.offers')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('coupon-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('coupons.index')); ?>" class="nav-link <?php echo e(request()->routeIs('coupons.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.coupons')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Inventory & Warehouse -->
                <?php if(auth()->user()->can('warehouse-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('warehouses.index')); ?>" class="nav-link <?php echo e(request()->routeIs('warehouses.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p><?php echo e(__('messages.warehouses')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Note Vouchers -->
                <?php if(auth()->user()->can('noteVoucherType-table') || auth()->user()->can('noteVoucher-table')): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('noteVoucherTypes.*') || request()->routeIs('noteVouchers.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link <?php echo e(request()->routeIs('noteVoucherTypes.*') || request()->routeIs('noteVouchers.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                <?php echo e(__('messages.note_vouchers')); ?>

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(auth()->user()->can('noteVoucherType-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('noteVoucherTypes.index')); ?>" class="nav-link <?php echo e(request()->routeIs('noteVoucherTypes.*') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.note_voucher_types')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php
                                $noteVouchertypes = App\Models\NoteVoucherType::get();
                                $locale = app()->getLocale();
                            ?>
                            <?php $__currentLoopData = $noteVouchertypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noteVouchertype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('noteVouchers.create', ['id' => $noteVouchertype->id])); ?>" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p><?php echo e($locale == 'ar' ? $noteVouchertype->name : $noteVouchertype->name_en); ?></p>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php if(auth()->user()->can('noteVoucher-table')): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('noteVouchers.index')); ?>" class="nav-link <?php echo e(request()->routeIs('noteVouchers.index') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.all_note_vouchers')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Delivery -->
                <?php if(auth()->user()->can('delivery-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('deliveries.index')); ?>" class="nav-link <?php echo e(request()->routeIs('deliveries.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-truck"></i>
                            <p><?php echo e(__('messages.delivery')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Points -->
                <?php if(auth()->user()->can('point-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('point-transactions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('point-transactions.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-coins"></i>
                            <p><?php echo e(__('messages.point_transactions')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Notifications -->
                <?php if(auth()->user()->can('notification-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('notifications.create')); ?>" class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-bell"></i>
                            <p><?php echo e(__('messages.notifications')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Business Types (if exists) -->
                <?php if(isset($user) && method_exists($user, 'can') && $user->can('businessType-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('businessTypes.index')); ?>" class="nav-link <?php echo e(request()->routeIs('businessTypes.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-building"></i>
                            <p><?php echo e(__('messages.business_types')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Pages -->
                <?php if(auth()->user()->can('page-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('pages.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pages.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p><?php echo e(__('messages.pages')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Taxes -->
                <li class="nav-item">
                    <a href="<?php echo e(route('taxes.index')); ?>" class="nav-link <?php echo e(request()->routeIs('taxes.*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-percentage"></i>
                        <p><?php echo e(__('messages.taxes')); ?></p>
                    </a>
                </li>

                <!-- CRVs -->
                <li class="nav-item">
                    <a href="<?php echo e(route('crvs.index')); ?>" class="nav-link <?php echo e(request()->routeIs('crvs.*') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p><?php echo e(__('messages.crvs')); ?></p>
                    </a>
                </li>

                <!-- Reports -->
                <?php if(auth()->user()->can('order-report') || auth()->user()->can('products-report') || auth()->user()->can('users-report') || auth()->user()->is_super_admin): ?>
                    <li class="nav-item has-treeview <?php echo e(request()->routeIs('*_report') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link <?php echo e(request()->routeIs('*_report') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                <?php echo e(__('messages.reports')); ?>

                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(auth()->user()->can('order-report') || auth()->user()->is_super_admin): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('admin.tax-crv.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.tax-crv.index') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tax & Crv Reports</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                          
                            <?php if(auth()->user()->can('order-report') || auth()->user()->is_super_admin): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('order_report')); ?>" class="nav-link <?php echo e(request()->routeIs('order_report') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.order_reports')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(auth()->user()->can('users-report') || auth()->user()->is_super_admin): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('user_report')); ?>" class="nav-link <?php echo e(request()->routeIs('user_report') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.user_reports')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('products-report') || auth()->user()->is_super_admin): ?>
                                <li class="nav-item">
                                    <a href="<?php echo e(route('product_report')); ?>" class="nav-link <?php echo e(request()->routeIs('product_report') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p><?php echo e(__('messages.product_reports')); ?></p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Settings -->
                <?php if(auth()->user()->can('setting-table')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.setting.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.setting.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p><?php echo e(__('messages.settings')); ?></p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Admin Account -->
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.login.edit', auth()->user()->id)); ?>" class="nav-link <?php echo e(request()->routeIs('admin.login.edit') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-user-shield"></i>
                        <p><?php echo e(__('messages.admin_account')); ?></p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/includes/sidebar.blade.php ENDPATH**/ ?>