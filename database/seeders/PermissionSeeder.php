<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
        $permissions_admin = [
            // Role Management
            'role-table',
            'role-add',
            'role-edit',
            'role-delete',

            // Employee Management
            'employee-table',
            'employee-add',
            'employee-edit',
            'employee-delete',

            // Customer Management
            'customer-table',
            'customer-add',
            'customer-edit',
            'customer-delete',
         
            // Wholesale Management
            'wholeSale-table',
            'wholeSale-add',
            'wholeSale-edit',
            'wholeSale-delete',

            // Business Type Management (if needed)
            'businessType-table',
            'businessType-add',
            'businessType-edit',
            'businessType-delete',

            // Order Management
            'order-table',
            'order-add',
            'order-edit',
            'order-delete',

            // Banner Management
            'banner-table',
            'banner-add',
            'banner-edit',
            'banner-delete',
      
            // Point Management
            'point-table',
            'point-add',
            'point-edit',
            'point-delete',

            // Point Transaction Management
            'point-transaction-table',
            'point-transaction-add',
            'point-transaction-edit',
            'point-transaction-delete',

            // Delivery Management
            'delivery-table',
            'delivery-add',
            'delivery-edit',
            'delivery-delete',

            // Notification Management
            'notification-table',
            'notification-add',
            'notification-edit',
            'notification-delete',

            // Setting Management
            'setting-table',
            'setting-add',
            'setting-edit',
            'setting-delete',

            // Category Management
            'category-table',
            'category-add',
            'category-edit',
            'category-delete',

            // Unit Management
            'unit-table',
            'unit-add',
            'unit-edit',
            'unit-delete',

            // Brand Management
            'brand-table',
            'brand-add',
            'brand-edit',
            'brand-delete',

            // Product Management
            'product-table',
            'product-add',
            'product-edit',
            'product-delete',

            // Offer Management
            'offer-table',
            'offer-add',
            'offer-edit',
            'offer-delete',

            // Coupon Management
            'coupon-table',
            'coupon-add',
            'coupon-edit',
            'coupon-delete',

            // Warehouse Management
            'warehouse-table',
            'warehouse-add',
            'warehouse-edit',
            'warehouse-delete',

            // Note Voucher Type Management
            'noteVoucherType-table',
            'noteVoucherType-add',
            'noteVoucherType-edit',
            'noteVoucherType-delete',

            // Note Voucher Management
            'noteVoucher-table',
            'noteVoucher-add',
            'noteVoucher-edit',
            'noteVoucher-delete',
       
            // Page Management
            'page-table',
            'page-add',
            'page-edit',
            'page-delete',

            // Tax Management
            'tax-table',
            'tax-add',
            'tax-edit',
            'tax-delete',

            // CRV Management
            'crv-table',
            'crv-add',
            'crv-edit',
            'crv-delete',
            
            // Reports
            'order-report',
            'products-report',
            'users-report',
            'category-report-view',
            'category-report-export',
            'customer-report-view',
            'customer-report-export',
        ];


         foreach ($permissions_admin as $permission_ad) {
            Permission::create(['name' => $permission_ad, 'guard_name' => 'admin']);
        }
    }
}
