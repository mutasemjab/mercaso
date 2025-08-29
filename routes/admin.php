<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BusinessTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\WholeSaleController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CrvController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\NoteVoucherTypeController;
use App\Http\Controllers\Admin\NoteVoucherController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PointTransactionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Reports\AllProductReportController;
use App\Http\Controllers\Reports\InventoryReportController;
use App\Http\Controllers\Reports\OrderReportController;
use App\Http\Controllers\Reports\ProductReportController;
use App\Http\Controllers\Reports\TaxReportController;
use App\Http\Controllers\Reports\UserReportController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define('PAGINATION_COUNT', 11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    //Search Product in Jquery
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/get-prices/{id}', [ProductController::class, 'getPrices'])->name('products.getPrices');

    Route::get('/search-users', [CustomerController::class, 'search'])->name('search.users');
    Route::get('/user/{id}/addresses', [CustomerController::class, 'addresses'])->name('user.addresses');
    Route::get('delivery/{deliveryId}/fee', [CustomerController::class, 'getFee']);



    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

        // Import From Excel
        Route::get('/products/import/show', [ProductController::class, 'showImportPage'])->name('products.import.show');
        Route::post('/products/import', [ProductController::class, 'storeFromExcel'])->name('products.import');
        Route::get('/products/import/sample', [ProductController::class, 'downloadSample'])->name('products.import.sample');

        // Import From Excel
        Route::get('/noteVouchers/import/show', [NoteVoucherController::class, 'showImportPage'])->name('noteVouchers.import.show');
        Route::post('/noteVouchers/import', [NoteVoucherController::class, 'storeFromExcel'])->name('noteVouchers.import');
        Route::get('/noteVouchers/import/sample', [NoteVoucherController::class, 'downloadSample'])->name('noteVouchers.import.sample');

        // Import From Excel
        Route::get('/wholeSale/import/show', [WholeSaleController::class, 'showImportPage'])->name('admin.wholeSale.import.show');
        Route::post('/wholeSale/import', [WholeSaleController::class, 'storeFromExcel'])->name('admin.wholeSale.import');
        Route::get('/wholeSale/import/sample', [WholeSaleController::class, 'downloadSample'])->name('admin.wholeSale.sample');


        /*         start  customer                */
        Route::get('/customer/index', [CustomerController::class, 'index'])->name('admin.customer.index');
        Route::get('/customer/show/{id}', [CustomerController::class, 'show'])->name('admin.customer.show');
        Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('admin.customer.edit');
        Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('admin.customer.update');
        Route::post('/customer/ajax_search', [CustomerController::class, 'ajax_search'])->name('admin.customer.ajax_search');
        Route::get('/customer/export', [CustomerController::class, 'export'])->name('admin.customer.export');
        /*         end  customer                */

        /*         start  wholeSale                */
        Route::get('/wholeSale/index', [WholeSaleController::class, 'index'])->name('admin.wholeSale.index');
        Route::get('/wholeSale/show/{id}', [WholeSaleController::class, 'show'])->name('admin.wholeSale.show');
        Route::get('/wholeSale/edit/{id}', [WholeSaleController::class, 'edit'])->name('admin.wholeSale.edit');
        Route::post('/wholeSale/update/{id}', [WholeSaleController::class, 'update'])->name('admin.wholeSale.update');
        Route::post('/wholeSale/ajax_search', [WholeSaleController::class, 'ajax_search'])->name('admin.wholeSale.ajax_search');
        Route::get('/wholeSale/export', [WholeSaleController::class, 'export'])->name('admin.wholeSale.export');
        /*         end  wholeSale                */


        Route::name('admin.')->group(function() {
            Route::resource('role', RoleController::class);
            Route::post('role/delete', [RoleController::class, 'delete'])->name('role.delete');
            Route::resource('employee', EmployeeController::class);
        });


        /*         start  update login admin                 */
        Route::get('/admin/edit/{id}', [LoginController::class, 'editlogin'])->name('admin.login.edit');
        Route::post('/admin/update/{id}', [LoginController::class, 'updatelogin'])->name('admin.login.update');
        /*         end  update login admin                */


        Route::get('/permissions/{guard_name}', function ($guard_name) {
            return response()->json(Permission::where('guard_name', $guard_name)->get());
        });


        /*         start  setting                */
        Route::get('/setting/index', [SettingController::class, 'index'])->name('admin.setting.index');
        Route::get('/setting/create', [SettingController::class, 'create'])->name('admin.setting.create');
        Route::post('/setting/store', [SettingController::class, 'store'])->name('admin.setting.store');
        Route::get('/setting/edit/{id}', [SettingController::class, 'edit'])->name('admin.setting.edit');
        Route::post('/setting/update/{id}', [SettingController::class, 'update'])->name('admin.setting.update');

        /*         end  setting                */


        // Notification
        Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');

        Route::prefix('pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('pages.index');
            Route::get('/create', [PageController::class, 'create'])->name('pages.create');
            Route::post('/store', [PageController::class, 'store'])->name('pages.store');
            Route::get('/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
            Route::put('/update/{id}', [PageController::class, 'update'])->name('pages.update');
            Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
        });

        //Reports
        Route::get('/order_report', [OrderReportController::class, 'index'])->name('order_report');
        Route::get('/user_report', [UserReportController::class, 'index'])->name('user_report');
        Route::get('/product_report', [ProductReportController::class, 'allProducts'])->name('product_report');



        // Resource Route
        Route::resource('noteVoucherTypes', NoteVoucherTypeController::class);
        Route::resource('noteVouchers', NoteVoucherController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('offers', OfferController::class);
        Route::resource('coupons', CouponController::class);
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('units', UnitController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('deliveries', DeliveryController::class);
        Route::resource('businessTypes', BusinessTypeController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('banners', BannerController::class);
        Route::resource('taxes', TaxController::class);
        Route::resource('crvs', CrvController::class);
         Route::resource('point-transactions', PointTransactionController::class);
    
    // AJAX Route for getting user points
         Route::get('/ajax/user-points', [PointTransactionController::class, 'getUserPoints'])
         ->name('point-transactions.get-user-points');
        Route::get('deliveries/{delivery}/availabilities', [DeliveryController::class, 'manageAvailabilities'])
        ->name('deliveries.availabilities');
    });
});



Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
