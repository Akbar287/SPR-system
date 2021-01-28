<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes(['register' => false]);
Route::group(['middleware' => ['auth', 'cekStatus']], function(){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/activities', 'HomeController@activities')->name('activities');

    Route::get('/search', 'HomeController@search')->name('Search');
    Route::get('/balance', 'HomeController@search')->name('Search');
    Route::get('/graph', 'HomeController@Graph')->name('Search');

    Route::get('/print', 'PrintController@index')->name('print')->middleware('cekAdmin');
    Route::get('/print/ju', 'PrintController@ju')->name('print')->middleware('cekAdmin', 'cekPrint');
    Route::get('/print/ak', 'PrintController@ak')->name('print')->middleware('cekAdmin', 'cekPrint');
    Route::get('/print/pm', 'PrintController@pm')->name('print')->middleware('cekAdmin', 'cekPrint');
    Route::get('/print/lr', 'PrintController@lr')->name('print')->middleware('cekAdmin', 'cekPrint');
    Route::get('/print/nc', 'PrintController@nc')->name('print')->middleware('cekAdmin', 'cekPrint');

    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::post('/profile', 'HomeController@ChangeProfile')->name('profile');

    Route::get('/licenses', 'HomeController@license')->name('license');
    Route::get('/settings', 'HomeController@setting')->name('setting');
    Route::post('/settings/reset', 'HomeController@reset')->name('setting');

    Route::get('/financial', 'FinancialController@index')->name('financial')->middleware('cekAdmin');

    Route::get('/accounting', 'AccountingController@index')->name('accounting')->middleware('cekAdmin');
    Route::post('/accounting', 'AccountingController@store')->name('accounting')->middleware('cekAdmin');
    Route::get('/accounting/create', 'AccountingController@create')->name('accounting')->middleware('cekAdmin');

    Route::get('/income', 'FinancialController@income')->name('income')->middleware('cekAdmin');
    Route::post('/income', 'FinancialController@incomestore')->name('income')->middleware('cekAdmin');
    Route::get('/income/create', 'FinancialController@incomecreate')->name('income')->middleware('cekAdmin');
    Route::get('/income/{id}', 'FinancialController@incomeedit')->name('income')->middleware('cekAdmin', 'cekIncome');
    Route::patch('/income/{id}', 'FinancialController@incomeupdate')->name('income')->middleware('cekAdmin', 'cekIncome');
    Route::delete('/income/{id}', 'FinancialController@incomedelete')->name('income')->middleware('cekAdmin', 'cekIncome');

    Route::get('/outcome', 'FinancialController@outcome')->name('outcome')->middleware('cekAdmin');
    Route::post('/outcome', 'FinancialController@storeoutcome')->name('outcome')->middleware('cekAdmin');
    Route::get('/outcome/create', 'FinancialController@createoutcome')->name('outcome')->middleware('cekAdmin');
    Route::get('/outcome/{id}', 'FinancialController@editoutcome')->name('outcome')->middleware('cekAdmin', 'cekOutcome');
    Route::patch('/outcome/{id}', 'FinancialController@updateoutcome')->name('outcome')->middleware('cekAdmin', 'cekOutcome');
    Route::delete('/outcome/{id}', 'FinancialController@deleteoutcome')->name('outcome')->middleware('cekAdmin', 'cekOutcome');

    Route::get('/purchase/hpp', 'FinancialController@hpp')->name('purchase')->middleware('cekAdmin');
    Route::post('/purchase/hpp', 'FinancialController@hppstore')->name('purchase')->middleware('cekAdmin');

    Route::get('/purchase', 'FinancialController@purchase')->name('purchase')->middleware('cekAdmin');
    Route::get('/purchase/create', 'FinancialController@createpurchase')->name('purchase')->middleware('cekAdmin');
    Route::post('/purchase', 'FinancialController@storepurchase')->name('purchase')->middleware('cekAdmin');
    Route::get('/purchase/{id}', 'FinancialController@editpurchase')->name('purchase')->middleware('cekAdmin', 'cekPurchase');
    Route::patch('/purchase/{id}', 'FinancialController@updatepurchase')->name('purchase')->middleware('cekAdmin', 'cekPurchase');
    Route::delete('/purchase/{id}', 'FinancialController@deletepurchase')->name('purchase')->middleware('cekAdmin', 'cekPurchase');

    Route::get('/capital', 'FinancialController@capital')->name('capital')->middleware('cekAdmin');
    Route::get('/capital/add', 'FinancialController@createCapital')->name('capital')->middleware('cekAdmin');
    Route::post('/capital', 'FinancialController@storecapital')->name('capital')->middleware('cekAdmin');
    Route::get('/capital/{id}', 'FinancialController@editcapital')->name('capital')->middleware('cekAdmin', 'cekCapital');
    Route::patch('/capital/{id}', 'FinancialController@updatecapital')->name('capital')->middleware('cekAdmin', 'cekCapital');
    Route::delete('/capital/{id}', 'FinancialController@deletecapital')->name('capital')->middleware('cekAdmin', 'cekCapital');

    Route::get('/statement', 'FinancialController@statement')->name('statement')->middleware('cekAdmin');

    Route::get('/product', 'ProductsController@index')->name('product')->middleware('cekAdmin');
    Route::get('/stock', 'ProductsController@stock')->name('product')->middleware('cekAdmin');
    Route::post('/stock', 'ProductsController@restock')->name('product')->middleware('cekAdmin');

    Route::get('/products', 'ProductsController@products')->name('product')->middleware('cekAdmin');
    Route::post('/products', 'ProductsController@storeproducts')->name('product')->middleware('cekAdmin');
    Route::get('/products/create', 'ProductsController@createproducts')->name('product')->middleware('cekAdmin');
    Route::get('/products/{id}', 'ProductsController@showproducts')->name('product')->middleware('cekAdmin', 'cekProduct');
    Route::patch('/products/{id}', 'ProductsController@updateproducts')->name('product')->middleware('cekAdmin', 'cekProduct');
    Route::delete('/products/{id}', 'ProductsController@destroyproducts')->name('product')->middleware('cekAdmin', 'cekProduct');

    Route::get('/category', 'ProductsController@category')->name('category')->middleware('cekAdmin');
    Route::post('/category', 'ProductsController@storeCategory')->name('category')->middleware('cekAdmin');
    Route::get('/category/create', 'ProductsController@createCategory')->name('category')->middleware('cekAdmin');
    Route::get('/category/{id}', 'ProductsController@showcategory')->name('category')->middleware('cekAdmin', 'cekCategory');
    Route::patch('/category/{id}', 'ProductsController@updateCategory')->name('category')->middleware('cekAdmin', 'cekCategory');
    Route::delete('/category/{id}', 'ProductsController@destroyCategory')->name('category')->middleware('cekAdmin', 'cekCategory');

    Route::get('/producted', 'ProductsController@producted')->name('producted')->middleware('cekAdmin');
    Route::get('/producted/{id}', 'ProductsController@showproducted')->name('producted')->middleware('cekAdmin', 'cekProducted');
    Route::get('/producted/show/{id}', 'ProductsController@showallproducted')->name('producted')->middleware('cekAdmin', 'cekProducted');
    Route::post('/producted/{id}', 'ProductsController@storeproducted')->name('producted')->middleware('cekAdmin', 'cekProducted');

    Route::get('/supplier', 'VendorController@index')->name('vendor')->middleware('cekAdmin');
    Route::get('/supplier/create', 'VendorController@create')->name('vendor')->middleware('cekAdmin');
    Route::post('/supplier', 'VendorController@store')->name('vendor')->middleware('cekAdmin');
    Route::get('/supplier/{id}', 'VendorController@show')->name('vendor')->middleware('cekAdmin', 'cekSupplier');
    Route::patch('/supplier/{id}', 'VendorController@update')->name('vendor')->middleware('cekAdmin', 'cekSupplier');
    //Route::delete('/supplier/{id}', 'VendorController@destroy')->name('vendor')->middleware('cekAdmin', 'cekSupplier');

    Route::get('/discount', 'DiscountController@index')->name('Discount')->middleware('cekAdmin');
    Route::get('/discount/create', 'DiscountController@create')->name('Discount')->middleware('cekAdmin');
    Route::post('/discount', 'DiscountController@store')->name('Discount')->middleware('cekAdmin');
    Route::get('/discount/{id}', 'DiscountController@show')->name('Discount')->middleware('cekAdmin', 'cekDiskon');
    Route::patch('/discount/{id}', 'DiscountController@update')->name('Discount')->middleware('cekAdmin', 'cekDiskon');
    Route::delete('/discount/{id}', 'DiscountController@destroy')->name('Discount')->middleware('cekAdmin', 'cekDiskon');

    Route::get('/expense', 'ExpenseController@index')->name('Expense')->middleware('cekAdmin');
    Route::get('/expense/create', 'ExpenseController@create')->name('Expense')->middleware('cekAdmin');
    Route::post('/expense', 'ExpenseController@store')->name('Expense')->middleware('cekAdmin');
    Route::get('/expense/{id}', 'ExpenseController@show')->name('Expense')->middleware('cekAdmin', 'cekExpense');
    Route::patch('/expense/{id}', 'ExpenseController@update')->name('Expense')->middleware('cekAdmin', 'cekExpense');
    Route::delete('/expense/{id}', 'ExpenseController@destroy')->name('Expense')->middleware('cekAdmin', 'cekExpense');

    Route::get('/salary', 'SalaryController@index')->name('Salary')->middleware('cekAdmin');
    Route::get('/salary/create', 'SalaryController@create')->name('Salary')->middleware('cekAdmin');
    Route::post('/salary', 'SalaryController@store')->name('Salary')->middleware('cekAdmin');
    Route::get('/salary/{id}', 'SalaryController@show')->name('Salary')->middleware('cekAdmin', 'cekSalary');
    Route::get('/salary/{id}/print', 'SalaryController@print')->name('Salary')->middleware('cekAdmin', 'cekSalary');
    Route::patch('/salary/{id}', 'SalaryController@update')->name('Salary')->middleware('cekAdmin', 'cekSalary');
    Route::delete('/salary/{id}', 'SalaryController@destroy')->name('Salary')->middleware('cekAdmin', 'cekSalary');

    Route::get('/reseller', 'ResellerController@index')->name('reseller')->middleware('cekAdmin');
    Route::get('/reseller/create', 'ResellerController@create')->name('reseller')->middleware('cekAdmin');
    Route::post('/reseller', 'ResellerController@store')->name('reseller')->middleware('cekAdmin');
    Route::get('/reseller/{id}', 'ResellerController@show')->name('reseller')->middleware('cekAdmin', 'ceklistAdmin');
    Route::patch('/reseller/{id}', 'ResellerController@update')->name('reseller')->middleware('cekAdmin', 'ceklistAdmin');
    //Route::delete('/reseller/{id}', 'ResellerController@destroy')->name('reseller')->middleware('cekAdmin', 'ceklistAdmin');

    Route::get('/admin', 'AdminController@index')->name('admin')->middleware('cekAdmin');
    Route::get('/admin/create', 'AdminController@create')->name('admin')->middleware('cekAdmin');
    Route::post('/admin', 'AdminController@store')->name('admin')->middleware('cekAdmin');
    Route::get('/admin/{id}', 'AdminController@show')->name('admin')->middleware('cekAdmin', 'ceklistAdmin');
    Route::patch('/admin/{id}', 'AdminController@update')->name('admin')->middleware('cekAdmin', 'ceklistAdmin');
    //Route::delete('/admin/{id}', 'AdminController@destroy')->name('admin', 'ceklistAdmin');

    Route::get('/market', 'MarketController@index')->name('market')->middleware('cekAdmin');
    Route::get('/market/create', 'MarketController@create')->name('market')->middleware('cekAdmin');
    Route::post('/market', 'MarketController@store')->name('market')->middleware('cekAdmin');
    Route::get('/market/{id}', 'MarketController@show')->name('market')->middleware('cekAdmin', 'cekMarket');
    Route::patch('/market/{id}', 'MarketController@update')->name('market')->middleware('cekAdmin', 'cekMarket');
    //Route::delete('/market/{id}', 'MarketController@destroy')->name('market')->middleware('cekAdmin', 'cekMarket');

    Route::get('/materials', 'MaterialController@index')->name('material')->middleware('cekAdmin');
    Route::get('/materials/create', 'MaterialController@create')->name('material')->middleware('cekAdmin');
    Route::post('/materials', 'MaterialController@store')->name('material')->middleware('cekAdmin');
    Route::get('/materials/{id}', 'MaterialController@show')->name('material')->middleware('cekAdmin', 'cekMaterial');
    Route::patch('/materials/{id}', 'MaterialController@update')->name('material')->middleware('cekAdmin', 'cekMaterial');
    Route::delete('/materials/{id}', 'MaterialController@destroy')->name('material')->middleware('cekAdmin', 'cekMaterial');

    Route::get('/pesanan', 'CartController@pesananindex')->name('pesanan')->middleware('cekAdmin');

    Route::get('/penjualan', 'FinancialController@penjualanindex')->name('penjualan')->middleware('cekAdmin');

    Route::get('/store', 'StoreController@index')->name('Store')->middleware('cekReseller');
    Route::get('/store/request', 'StoreController@request')->name('store')->middleware('cekReseller');
    Route::get('/store/{id}', 'StoreController@show')->name('store')->middleware('cekReseller');
    Route::post('/store', 'StoreController@store')->name('store')->middleware('cekReseller');

    Route::get('/cart', 'CartController@index')->name('cart')->middleware('cekReseller');
    Route::get('/cart/create', 'CartController@create')->name('cart')->middleware('cekReseller');
    Route::post('/cart', 'CartController@show')->name('cart')->middleware('cekReseller');
    Route::post('/cart/create', 'CartController@store')->name('cart')->middleware('cekReseller');

    Route::post('/cart/product/{id}', 'CartController@product')->name('cart')->middleware('cekAdmin', 'cekCart', 'cekOrder');
    Route::post('/cart/bring/{id}', 'CartController@bring')->name('cart')->middleware('cekAdmin', 'cekCart', 'cekOrder');
    Route::post('/cart/to/{id}', 'CartController@to')->name('cart')->middleware('cekCart', 'cekOrder');

    Route::get('/cart/status/{id}', 'CartController@statusshow')->name('status')->middleware('cekCart', 'cekOrder');
    Route::get('/cart/status/{id}/order', 'CartController@orderStatus')->name('status')->middleware('cekCart', 'cekOrder');
    Route::get('/cart/status/{id}/return', 'CartController@orderReturn')->name('status')->middleware('cekCart', 'cekOrder', 'cekAdmin');
    Route::post('/cart/status/{id}/return', 'CartController@postReturn')->name('status')->middleware('cekCart', 'cekOrder', 'cekAdmin');
    Route::post('/cart/done/{id}', 'CartController@doneCart')->name('status')->middleware('cekAdmin', 'cekCart', 'cekOrder');
    Route::get('/cart/cetak/{id}', 'CartController@cetak')->name('status')->middleware('cekCart', 'cekOrder');
    Route::delete('/cart/cancel/{id}', 'CartController@statusdestroy')->name('status')->middleware('cekReseller', 'cekCart', 'cekOrder');

    // Route::get('/sales', 'FinancialController@sales')->name('sales')->middleware('cekAdmin');
    // Route::post('/sales', 'FinancialController@storesales')->name('sales')->middleware('cekAdmin');
    // Route::get('/sales/create', 'FinancialController@createsales')->name('sales')->middleware('cekAdmin');
    // Route::get('/sales/{id}', 'FinancialController@editsales')->name('sales')->middleware('cekAdmin');
    // Route::patch('/sales/{id}', 'FinancialController@updatesales')->name('sales')->middleware('cekAdmin');
    // Route::delete('/sales/{id}', 'FinancialController@deletesales')->name('sales')->middleware('cekAdmin');

});
