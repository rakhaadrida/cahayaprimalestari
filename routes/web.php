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

Route::get('/', 'DashboardController@index')->name('dashboard');

Route::resource('supplier', 'SupplierController');
Route::resource('customer', 'CustomerController');
Route::resource('barang', 'BarangController');
Route::resource('gudang', 'GudangController');
Route::resource('harga', 'HargaController');
Route::resource('po', 'PurchaseOrderController');

Route::get('/barang/harga/{id}', 'BarangController@harga')->name('hargaBarang');
Route::post('/barang/storeHarga', 'BarangController@storeHarga')->name('storeHarga');
