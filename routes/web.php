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

// Harga Barang
Route::get('/barang/harga/{id}', 'BarangController@harga')->name('hargaBarang');
Route::post('/barang/storeHarga', 'BarangController@storeHarga')->name('storeHarga');

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