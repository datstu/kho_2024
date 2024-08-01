<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShippingOrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FbWebHookController;
use App\Http\Controllers\CategoryCallController;
use App\Http\Controllers\LadipageController;
use App\Http\Controllers\SrcPageController;
use App\Http\Controllers\GroupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware('admin-auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('product');

    Route::get('/home',  [HomeController::class, 'index'])->name('home');
    
    //route thực phẩm đông lạnh
    Route::get('/danh-sach-san-pham',  [ProductController::class, 'index'])->name('product');
    Route::get('/them-san-pham',  [ProductController::class, 'addProduct'])->name('add-product');
    Route::get('/danh-muc-san-pham',  [CategoryController::class, 'index'])->name('category');
    Route::get('/them-danh-muc',  [CategoryController::class, 'add'])->name('add-category');
    
    Route::post('/save-category',[CategoryController::class,'save'])->name('save-category');
    Route::get('/update-category/{id}',[CategoryController::class,'viewUpdate'])->name('update-category');
    Route::get('/delete-category/{id}',  [CategoryController::class, 'delete'])->name('delete-category');
    Route::get('/search-category',  [CategoryController::class, 'search'])->name('search-category');
    
    Route::post('/save',[ProductController::class,'saveProduct'])->name('save-product');
    Route::get('/update/{id}',[ProductController::class,'viewUpdate'])->name('update-product');
    Route::get('/delete/{id}',  [ProductController::class, 'delete'])->name('delete-product');
    Route::get('/search',  [ProductController::class, 'search'])->name('search-product');
    
    // nhập hàng
    Route::get('/nhap-hang',  [ProductController::class, 'setProducts']);
    Route::get('nhap-hang-theo-thang',  [ProductController::class, 'setProductsByMonth'])->name('nhap-hang-theo-thang');
    Route::get('nhap-hang-theo-nam',  [ProductController::class, 'setProductsByYear'])->name('nhap-hang-theo-nam');
    
    
    // Route::get('/them-san-pham',  [ProductController::class, 'addProduct'])->name('add-product');
    // Route::get('/danh-muc-san-pham',  [CategoryController::class, 'index'])->name('category');
    // Route::get('/them-danh-muc',  [CategoryController::class, 'add'])->name('add-category');
    
    /** đơn hàng */
    Route::get('/don-hang',  [OrdersController::class, 'index'])->name('order');
    Route::get('/them-don-hang/{saleId?}',  [OrdersController::class, 'add'])->name('add-orders');
    Route::post('/save-orders',[OrdersController::class,'save'])->name('save-orders');
    Route::get('/get-ward-by-id',[AddressController::class,'getWardById'])->name('get-ward-by-id');
    Route::get('/get-district-by-id',[AddressController::class,'getDistrictById'])->name('get-district-by-id');
    
    Route::get('/search-order',  [OrdersController::class, 'search'])->name('search-order');
    Route::get('/update-order/{id}',[OrdersController::class,'viewUpdate'])->name('update-order');
    Route::get('/delete-order/{id}',  [OrdersController::class, 'delete'])->name('delete-order');
    Route::get('/chi-tiet-don-hang/{id}',  [OrdersController::class, 'view'])->name('view-order');
    Route::get('/loc-don-hang',  [OrdersController::class, 'filterOrderByDate'])->name('filter-order');
    Route::get('/get-products-by-category-id',  [ProductController::class, 'getProductsByCategoryId'])->name('get-products-by-category-id');
    

    Route::get('/cap-nhat-thanh-vien/{id}',[UserController::class,'viewUpdate'])->name('update-user');
    Route::get('/delete-user/{id}',  [UserController::class, 'delete'])->name('delete-user');
    Route::get('/tim-thanh-vien',  [UserController::class, 'search'])->name('search-user');
    Route::get('/them-thanh-vien',  [UserController::class, 'add'])->name('add-user');
    Route::get('/quan-ly-thanh-vien',  [UserController::class, 'index'])->name('manage-user');
    Route::post('/save-user',[UserController::class,'save'])->name('save-user');

    Route::get('/tao-van-don/{id}',  [ShippingOrderController::class, 'createShipping'])->name('create-shipping');
    Route::post('/save-shipping-has',  [ShippingOrderController::class, 'createShippingHas'])->name('create-shipping-has');
    Route::get('/chi-tiet-van-don/{id}',  [ShippingOrderController::class, 'detailShippingOrder'])->name('detai-shipping-order');

    Route::get('/tac-nghiep-sale',  [SaleController::class, 'index'])->name('sale-index');
    Route::get('/tao-tac-nghiep-sale',  [SaleController::class, 'add'])->name('sale-add');
    Route::post('/tao-tac-nghiep-sale',  [SaleController::class, 'save'])->name('sale-care-save');
    Route::get('/cap-nhat-tac-nghiep-sale/{id}',  [SaleController::class, 'update'])->name('sale-care-update');
    Route::post('/cap-nhat-sale-ajax',  [SaleController::class, 'saveAjax'])->name('sale-save-ajax');
    Route::get('/tim-tac-nghiep-sale',  [SaleController::class, 'search'])->name('search-sale-care');
    Route::post('/cap-nhat-TNcan',  [SaleController::class, 'updateTNcan'])->name('update-salecare-TNcan');
    Route::post('/cap-nhat-assign-TNcan',  [SaleController::class, 'updateAssignTNSale'])->name('update-salecare-assign');
    Route::post('/id-order-new-check',  [SaleController::class, 'getIdOrderNewTNSale'])->name('get-salecare-idorder-new');
    
    Route::get('/xoa-sale-care/{id}',  [SaleController::class, 'delete'])->name('sale-delete');

    Route::get('/loai-TN-sale',  [CategoryCallController::class, 'index'])->name('category-call'); 
    Route::get('/tao-loai-TN-sale',  [CategoryCallController::class, 'add'])->name('category-call-add');
    Route::post('/save-loai-TN-sale',  [CategoryCallController::class, 'save'])->name('category-call-save');
    Route::get('/cap-nhat-loai-TN-sale/{id}',  [CategoryCallController::class, 'update'])->name('category-call-update');
    Route::get('/delete-category-call/{id}',  [CategoryCallController::class, 'delete'])->name('category-call-delete');
    
    Route::get('/call',  [CallController::class, 'index'])->name('call-index');
    Route::get('/tao-call',  [CallController::class, 'add'])->name('call-add');
    Route::post('/luu-call',  [CallController::class, 'save'])->name('call-save');
    Route::get('/cap-nhat-call/{id}',  [CallController::class, 'update'])->name('call-update');
    Route::get('/call-delete/{id}',  [CallController::class, 'delete'])->name('call-delete');

    Route::get('/cai-dat-chung',  [SettingController::class, 'index'])->name('setting-general');
    Route::post('/telegram-save',  [SettingController::class, 'telegramSave'])->name('telegram-save');
    Route::post('/pancake-save',  [SettingController::class, 'pancakeSave'])->name('pancake-save');
    Route::post('/ladi-save',  [SettingController::class, 'ladiSave'])->name('ladi-save');

    Route::get('/quan-ly-nguon',  [SrcPageController::class, 'index'])->name('manage-src');
    Route::get('/them-nguon',  [SrcPageController::class, 'add'])->name('add-src');
    
    Route::get('/quan-ly-nhom',  [GroupController::class, 'index'])->name('manage-group');
    Route::get('/them-nhom',  [GroupController::class, 'add'])->name('add-group');
    Route::get('/cap-nhat-nhom/{id}',  [GroupController::class, 'update'])->name('update-group');
    Route::post('/luu-nhom',  [GroupController::class, 'save'])->name('save-group');
    Route::get('/xoa-nhom/{id}',  [GroupController::class, 'delete'])->name('delete-group');
});

Route::get('/login',  [UserController::class, 'login'])->name('login');
Route::post('/login',  [UserController::class, 'postLogin'])->name('login-post');
Route::get('/log-out',  [UserController::class, 'logOut'])->name('log-out');

Route::get('/filter-total',  [HomeController::class, 'filterTotal'])->name('filter-total');
// Route::get('/filter-total-sales',  [HomeController::class, 'filterTotalSales'])->name('filter-total-sales');
Route::get('/filter-total-sales',  [HomeController::class, 'ajaxFilterDashboar'])->name('filter-total-sales');
Route::get('/filter-total-digital',  [HomeController::class, 'ajaxFilterDashboardDigital'])->name('filter-total-digital');

// Route::get('/test',  [TestController::class, 'hi'])->name('test');
// Route::get('/test',  [TestController::class, 'updateStatusOrderGHN'])->name('test');
// Route::get('/test',  [TestController::class, 'crawlerPancake'])->name('test');
Route::get('/test',  [TestController::class, 'crawlerGroup'])->name('test');
// Route::get('/test',  [TestController::class, 'updateStatusOrderGhnV2'])->name('test');

Route::get('/hiep',  [TestController::class, 'saveDataHiep'])->name('hiep');

Route::get('/webhook-fb', [FbWebHookController::class, 'webhook'])->name('webhook');