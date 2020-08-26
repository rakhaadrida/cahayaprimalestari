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
Route::post('/barangmasuk/process', 'BarangMasukController@process')->name('bm-process');
Route::post('/barangmasuk/create/{id}', 'BarangMasukController@create')->name('bm-create');

// Sales Order
Route::get('/so', 'SalesController@index')->name('so');
Route::post('/so/create/{id}', 'SalesController@create')->name('so-create');
Route::post('/so/process/{id}', 'SalesController@process')->name('so-process');