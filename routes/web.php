<?php

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

// Dashboard
Route::get('/', 'DashboardController@index')->name('dashboard');

//CRUD Master
Route::resource('supplier', 'SupplierController');
Route::resource('sales', 'SalesController');
Route::resource('customer', 'CustomerController');
Route::resource('barang', 'BarangController');
Route::resource('gudang', 'GudangController');
Route::resource('harga', 'HargaController');
// Route::resource('po', 'PurchaseOrderController');

// Detail Barang
Route::get('barang/detail/{id}', 'BarangController@detail')->name('detailBarang');

// Harga Barang
Route::get('/barang/harga/{id}', 'BarangController@harga')->name('hargaBarang');
Route::post('/barang/storeHarga', 'BarangController@storeHarga')->name('storeHarga');

// Stok Barang
Route::get('/barang/stok/{id}', 'BarangController@stok')->name('stokBarang');
Route::post('/barang/storeStok', 'BarangController@storeStok')->name('storeStok');

// Purchase Order
Route::get('/po', 'PurchaseController@index')->name('po');
Route::post('/po/create/{id}', 'PurchaseController@create')->name('po-create');
Route::post('/po/process/{id}', 'PurchaseController@process')->name('po-process');
Route::post('/po/update', 'PurchaseController@update')->name('po-update');
Route::get('/po/remove/{po}/{barang}', 'PurchaseController@remove')->name('po-remove');

// Barang Masuk
Route::get('/barangmasuk', 'BarangMasukController@index')->name('barangMasuk');
Route::post('/barangmasuk/create/{id}', 'BarangMasukController@create')->name('bm-create');
Route::post('/barangmasuk/process/{id}', 'BarangMasukController@process')->name('bm-process');
Route::get('/barangmasuk/remove/{bm}/{barang}', 'BarangMasukController@remove')
        ->name('bm-remove');

// Transfer Barang
Route::get('/transfer', 'TransferBarangController@index')->name('tb');
Route::post('/transfer/create/{id}', 'TransferBarangController@create')->name('tb-create');
Route::post('/transfer/process/{id}', 'TransferBarangController@process')->name('tb-process');
Route::get('/transfer/remove/{id}/{barang}/{asal}/{tujuan}', 'TransferBarangController@remove')
        ->name('tb-remove');

// Sales Order
Route::get('/so', 'SalesOrderController@index')->name('so');
Route::post('/so/create/{id}', 'SalesOrderController@create')->name('so-create');
Route::post('/so/process/{id}', 'SalesOrderController@process')->name('so-process');
Route::get('/so/remove/{id}/{barang}', 'SalesOrderController@remove')->name('so-remove');

// Surat Jalan
Route::get('/sj', 'SuratJalanController@index')->name('sj');
Route::post('/sj/show', 'SuratJalanController@show')->name('sj-show');
Route::post('/sj/process/{id}', 'SuratJalanController@process')->name('sj-process');