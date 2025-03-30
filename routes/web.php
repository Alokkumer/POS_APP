<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellController;
use App\Http\Middleware\TokenVerificationMiddleware;
use App\Http\Middleware\SessionAuthenticate;
use Illuminate\Support\Facades\Route;

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

Route::get('/',[HomeController::class, 'index']);

Route::post('/user-registration', [UserController::class, 'UserRegistration'])->name('user-registration');
Route::post('/user-login', [UserController::class, 'UserLogin'])->name('user-login');
//send otp
Route::post('/send-otp', [UserController::class, 'SendOTP'])->name('send-otp');
//VerifyOTP
Route::post('/verifyOtp', [UserController::class, 'VerifyOTP'])->name('verifyOtp');

Route::middleware(SessionAuthenticate::class)->group(function () {

    //dashboard page
    Route::get('/DashboardPage', [UserController::class, 'DashboardPage']);
    Route::get('/loginVerifyPage', [UserController::class, 'LoginVerifyPage']);
    Route::get('/user-logout', [UserController::class, 'UserLogout']);
    //ResetPassword
    Route::post('/restPassword', [UserController::class, 'ResetPassword']);

    //category
    Route::post('/create-category', [CategoryController::class, 'CreateCategory'])->name('create-category');
    Route::get('/list-category', [CategoryController::class, 'ListCategory'])->name('list-category');
    Route::post('/category-by-id', [CategoryController::class, 'GetCategoryById']);
    Route::post('/update-category', [CategoryController::class, 'UpdateCategory'])->name('update-category');
    Route::get('/delete-category/{id}', [CategoryController::class, 'DeleteCategory'])->name('delete-category');
    Route::get('/CategoryPage', [CategoryController::class, 'CategoryPage'])->name('CategoryPage');
    Route::get('/CategorySavePage', [CategoryController::class, 'CategorySavePage'])->name('CategorySavePage');

    
    //customer
    Route::post('/create-customer', [CustomerController::class, 'CreateCustomer'])->name('create-customer');
    Route::get('/list-customer', [CustomerController::class, 'ListCustomer'])->name('list-customer');
    Route::post('/customer-by-id', [CustomerController::class, 'GetCustomerById']);
    Route::post('/update-customer', [CustomerController::class, 'UpdateCustomer'])->name('update-customer');
    Route::get('/delete-customer/{id}', [CustomerController::class, 'DeleteCustomer'])->name('delete-customer');
    Route::get('/CustomerPage', [CustomerController::class, 'CustomerPage'])->name('CustomerPage');
    Route::get('/CustomerSavePage', [CustomerController::class, 'CustomerSavePage'])->name('CustomerSavePage');

    //product
    Route::post('/create-product', [ProductController::class, 'CreateProduct'])->name('create-product');
    Route::get('/list-product', [ProductController::class, 'ListProduct'])->name('list-product');
    Route::post('/product-by-id', [ProductController::class, 'ProductById']);
    Route::post('/update-product', [ProductController::class, 'UpdateProduct'])->name('update-product');
    Route::get('/delete-product/{id}', [ProductController::class, 'DeleteProduct'])->name('delete-product');
    Route::get('/ProductPage', [ProductController::class, 'ProductPage'])->name('ProductPage');
    Route::get('/ProductSavePage', [ProductController::class, 'ProductSavePage'])->name('ProductSavePage');

    //Invoice
    Route::post('/invoice-create', [InvoiceController::class, 'CreateInvoice'])->name('invoice-create');
    Route::get('/invoice-list', [InvoiceController::class, 'InvoiceList'])->name('invoice-list');
    Route::post('/invoice-details', [InvoiceController::class, 'InvoiceDetails'])->name('invoice-details');
    Route::get('/invoice-delete/{id}', [InvoiceController::class, 'InvoiceDelete'])->name('InvoiceDelete');
    Route::get('/InvoiceListPage',[InvoiceController::class,'InvoiceListPage'])->name('InvoiceListPage');
    
    //sell route
    Route::get('/create-sale',[SellController::class,'SalePage'])->name('salePage');


    //Dashboard summary
    Route::get('/dashboard-summary', [DashboardController::class, 'DashboardSummary'])->name('dashboard-summary');    
    Route::get('/reset-password', [UserController::class, 'ResetPasswordPage']);
});


//User pages all routes
Route::get('/login',[UserController::class,'Login'])->name('login');    
Route::get('/register',[UserController::class,'Registration'])->name('register');
Route::get('/send-otp',[UserController::class,'SendOtpPage'])->name('send-otp');
Route::get('/verify-otp',[UserController::class,'VerifyOtpPage'])->name('verify-otp');
