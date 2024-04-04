<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShippingOrderController;

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
    Route::get('/them-don-hang',  [OrdersController::class, 'add'])->name('add-orders');
    Route::post('/save-orders',[OrdersController::class,'save'])->name('save-orders');
    Route::get('/get-ward-by-id',[AddressController::class,'getWardById'])->name('get-ward-by-id');
    Route::get('/get-district-by-id',[AddressController::class,'getDistrictById'])->name('get-district-by-id');
    
    Route::get('/search-order',  [OrdersController::class, 'search'])->name('search-order');
    Route::get('/update-order/{id}',[OrdersController::class,'viewUpdate'])->name('update-order');
    Route::get('/delete-order/{id}',  [OrdersController::class, 'delete'])->name('delete-order');

    Route::get('/cap-nhat-thanh-vien/{id}',[UserController::class,'viewUpdate'])->name('update-user');
    Route::get('/delete-user/{id}',  [UserController::class, 'delete'])->name('delete-user');
    Route::get('/tim-thanh-vien',  [UserController::class, 'search'])->name('search-user');
    Route::get('/them-thanh-vien',  [UserController::class, 'add'])->name('add-user');
    Route::get('/quan-ly-thanh-vien',  [UserController::class, 'index'])->name('manage-user');
    Route::post('/save-user',[UserController::class,'save'])->name('save-user');

    Route::get('/tao-van-don/{id}',  [ShippingOrderController::class, 'createShipping'])->name('create-shipping');
    Route::post('/save-shipping-has',  [ShippingOrderController::class, 'createShippingHas'])->name('create-shipping-has');
    Route::get('/chi-tiet-van-don/{id}',  [ShippingOrderController::class, 'detailShippingOrder'])->name('detai-shipping-order');
});

Route::get('/login',  [UserController::class, 'login'])->name('login');
Route::post('/login',  [UserController::class, 'postLogin'])->name('login-post');
Route::get('/log-out',  [UserController::class, 'logOut'])->name('log-out');

Route::get('/filter-total',  [HomeController::class, 'filterTotal'])->name('filter-total');
Route::get('/filter-total-sales',  [HomeController::class, 'filterTotalSales'])->name('filter-total-sales');

