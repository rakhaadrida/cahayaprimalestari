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

Route::middleware(['auth', 'admin', 'roles'])->group(function() {
    // Dashboard
    Route::get('/', 'DashboardController@index')->name('dashboard');

    // Ganti Password
    Route::get('password', 'UserController@change')->name('user-change');
    Route::post('password/process', 'UserController@process')->name('user-process');

    Route::group(['roles'=>['ADMIN', 'SUPER']], function() {
        //CRUD Master
        Route::resource('supplier', 'SupplierController');
        Route::resource('sales', 'SalesController');
        Route::resource('customer', 'CustomerController');
        Route::resource('jenis', 'JenisBarangController');
        Route::resource('subjenis', 'SubjenisController');
        Route::resource('barang', 'BarangController');
        Route::resource('gudang', 'GudangController');
        Route::resource('harga', 'HargaController');
        // Route::resource('po', 'PurchaseOrderController');

        // Soft Deletes Suppllier
        Route::get('supplier/trash/all', 'SupplierController@trash')->name('sup-trash');
        Route::get('supplier/restore/{id}', 'SupplierController@restore')->name('sup-restore');
        Route::get('supplier/restore-all/all', 'SupplierController@restoreAll')
            ->name('sup-restoreAll');
        Route::get('supplier/hapus/{id}', 'SupplierController@hapus')->name('sup-hapus');
        Route::get('supplier/hapus-all/all', 'SupplierController@hapusAll')
            ->name('sup-hapusAll');
        
        // Soft Deletes Sales
        Route::get('sales/trash/all', 'SalesController@trash')->name('sales-trash');
        Route::get('sales/restore/{id}', 'SalesController@restore')->name('sales-restore');
        Route::get('sales/restore-all/all', 'SalesController@restoreAll')
            ->name('sales-restoreAll');
        Route::get('sales/hapus/{id}', 'SalesController@hapus')->name('sales-hapus');
        Route::get('sales/hapus-all/all', 'SalesController@hapusAll')
            ->name('sales-hapusAll');

        // Soft Deletes Customer
        Route::get('customer/trash/all', 'CustomerController@trash')->name('cus-trash');
        Route::get('customer/restore/{id}', 'CustomerController@restore')->name('cus-restore');
        Route::get('customer/restore-all/all', 'CustomerController@restoreAll')
            ->name('cus-restoreAll');
        Route::get('customer/hapus/{id}', 'CustomerController@hapus')->name('cus-hapus');
        Route::get('customer/hapus-all/all', 'CustomerController@hapusAll')
            ->name('cus-hapusAll');

        // Soft Deletes Jenis Barang
        Route::get('jenisbarang/trash/all', 'JenisBarangController@trash')->name('jb-trash');
        Route::get('jenisbarang/restore/{id}', 'JenisBarangController@restore')
            ->name('jb-restore');
        Route::get('jenisbarang/restore-all/all', 'JenisBarangController@restoreAll')
            ->name('jb-restoreAll');
        Route::get('jenisbarang/hapus/{id}', 'JenisBarangController@hapus')->name('jb-hapus');
        Route::get('jenisbarang/hapus-all/all', 'JenisBarangController@hapusAll')
            ->name('jb-hapusAll');

        // Soft Deletes Sub Jenis Barang
        Route::get('subjenis/trash/all', 'SubjenisController@trash')->name('sub-trash');
        Route::get('subjenis/restore/{id}', 'SubjenisController@restore')
            ->name('sub-restore');
        Route::get('subjenis/restore-all/all', 'SubjenisController@restoreAll')
            ->name('sub-restoreAll');
        Route::get('subjenis/hapus/{id}', 'SubjenisController@hapus')->name('sub-hapus');
        Route::get('subjenis/hapus-all/all', 'SubjenisController@hapusAll')
            ->name('sub-hapusAll');

        // Soft Deletes Barang
        Route::get('barang/trash/all', 'BarangController@trash')->name('barang-trash');
        Route::get('barang/restore/{id}', 'BarangController@restore')->name('barang-restore');
        Route::get('barang/restore-all/all', 'BarangController@restoreAll')
            ->name('barang-restoreAll');
        Route::get('barang/hapus/{id}', 'BarangController@hapus')->name('barang-hapus');
        Route::get('barang/hapus-all/all', 'BarangController@hapusAll')
            ->name('barang-hapusAll');

        // Soft Deletes Harga
        Route::get('harga/trash/all', 'HargaController@trash')->name('harga-trash');
        Route::get('harga/restore/{id}', 'HargaController@restore')->name('harga-restore');
        Route::get('harga/restore-all/all', 'HargaController@restoreAll')
            ->name('harga-restoreAll');
        Route::get('harga/hapus/{id}', 'HargaController@hapus')->name('harga-hapus');
        Route::get('harga/hapus-all/all', 'HargaController@hapusAll')
            ->name('harga-hapusAll');
        
        // Soft Deletes Gudang
        Route::get('gudang/trash/all', 'GudangController@trash')->name('gudang-trash');
        Route::get('gudang/restore/{id}', 'GudangController@restore')->name('gudang-restore');
        Route::get('gudang/restore-all/all', 'GudangController@restoreAll')
            ->name('gudang-restoreAll');
        Route::get('gudang/hapus/{id}', 'GudangController@hapus')->name('gudang-hapus');
        Route::get('gudang/hapus-all/all', 'GudangController@hapusAll')
            ->name('gudang-hapusAll');

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
        Route::get('/barangmasuk/index/{status}', 'BarangMasukController@index')->name('barangMasuk');
        Route::post('/barangmasuk/create/{id}', 'BarangMasukController@create')->name('bm-create');
        Route::post('/barangmasuk/process/{id}/{status}','BarangMasukController@process')->name('bm-process');
        // Route::post('/barangmasuk/process/{id}', 'BarangMasukController@process')->name('bm-process');
        Route::get('/barangmasuk/cetak/{id}', 'BarangMasukController@cetak')->name('bm-cetak');
        Route::post('/barangmasuk/update/{bm}/{barang}/{id}', 'BarangMasukController@update')
            ->name('bm-update');
        Route::get('/barangmasuk/remove/{bm}/{barang}', 'BarangMasukController@remove')
            ->name('bm-remove');
        Route::get('/barangmasuk/reset/{bm}', 'BarangMasukController@reset')->name('bm-reset');
        Route::get('/barangmasuk/change', 'BarangMasukController@change')->name('bm-change');
        Route::get('/barangmasuk/change/show', 'BarangMasukController@show')
            ->name('bm-show');
        Route::post('/barangmasuk/change/status/{id}', 'BarangMasukController@status')
            ->name('bm-status');
        Route::post('/barangmasuk/change/edit/{id}', 'BarangMasukController@edit')
            ->name('bm-edit');
        Route::post('/barangmasuk/change/update', 'BarangMasukController@update')
            ->name('bm-update');
        
        // Cetak Barang Masuk
        Route::get('/cetak-bm/{status}/{awal}/{akhir}', 'CetakBMController@index')
            ->name('cetak-bm');
        Route::post('/cetak-bm/detail/{id}', 'CetakBMController@detail')
            ->name('cetak-bm-detail');
        Route::post('/cetak-bm/process', 'CetakBMController@process')
            ->name('cetak-bm-process');
        Route::get('/cetak-bm-all/{awal}/{akhir}', 'CetakBMController@cetak')
            ->name('cetak-bm-all');

        // Transfer Barang
        Route::get('/transfer', 'TransferBarangController@index')->name('tb');
        Route::post('/transfer/create/{id}', 'TransferBarangController@create')
            ->name('tb-create');
        Route::post('/transfer/process/{id}', 'TransferBarangController@process')
            ->name('tb-process');
        Route::get('/transfer/remove/{id}/{barang}/{asal}/{tujuan}','TransferBarangController@remove')->name('tb-remove');

        // Sales Order
        Route::get('/so/index/{status}', 'SalesOrderController@index')->name('so');
        Route::post('/so/create/{id}', 'SalesOrderController@create')->name('so-create');
        Route::post('/so/process/{id}/{status}', 'SalesOrderController@process')
            ->name('so-process');
        Route::get('/so/cetak/{id}', 'SalesOrderController@cetak')->name('so-cetak');
        Route::get('/so/cetak-ttr/{id}', 'SalesOrderController@tandaterima')->name('so-ttr');
        Route::get('/so/remove/{id}/{barang}','SalesOrderController@remove')->name('so-remove');
        // Route::get('/so/change', 'SalesOrderController@change')->name('so-change');
        // Route::get('/so/change/show', 'SalesOrderController@show')->name('so-show');
        Route::post('/so/change/status/{id}', 'SalesOrderController@status')->name('so-status');
        Route::post('/so/change/edit/{id}', 'SalesOrderController@edit')->name('so-edit');
        Route::post('/so/change/update', 'SalesOrderController@update')->name('so-update');

        // Transaksi Harian
        Route::get('/transaksi', 'TransaksiController@index')->name('trans');
        Route::get('/transaksi/show', 'TransaksiController@show')->name('trans-show');
        Route::post('/transaksi/detail/{id}', 'TransaksiController@detail')
            ->name('trans-detail');
        
        // Retur Barang
        Route::get('/retur/stok', 'ReturController@index')->name('retur-stok');
        Route::get('/retur/index-jual', 'ReturController@createPenjualan')
            ->name('ret-index-jual');
        Route::post('/retur/index-jual/detail', 'ReturController@showCreateJual')
            ->name('ret-detail-jual');
        Route::post('/retur/index-jual/process/{id}', 'ReturController@storeJual')
            ->name('ret-process-jual');
        Route::get('retur/penjualan', 'ReturController@dataReturJual')->name('retur-jual');
        Route::post('retur/penjualan', 'ReturController@dataReturJual')->name('home-jual');
        Route::post('retur/penjualan/show', 'ReturController@showReturJual')
            ->name('retur-jual-show');
        Route::post('retur/penjualan/kirim', 'ReturController@storeKirimJual')
            ->name('retur-jual-process');
        Route::get('/retur/penjualan/cetak/{id}', 'ReturController@cetakKirimJual')
            ->name('retur-jual-cetak');
        Route::get('/retur/penjualan/cetak-ttr/{id}', 'ReturController@ttrKirimJual')
            ->name('retur-jual-cetak');
        Route::get('/retur/index-beli', 'ReturController@createPembelian')
            ->name('ret-index-beli');
        Route::post('/retur/index-beli/detail', 'ReturController@showCreateBeli')
            ->name('ret-detail-beli');
        Route::post('/retur/index-beli/process/{id}', 'ReturController@storeBeli')
            ->name('ret-process-beli');
        Route::get('retur/pembelian', 'ReturController@dataReturBeli')->name('retur-beli');
        Route::post('retur/pembelian', 'ReturController@dataReturBeli')->name('home-beli');
        Route::post('retur/pembelian/show', 'ReturController@showReturBeli')
            ->name('retur-beli-show');
        Route::post('retur/pembelian/terima', 'ReturController@storeTerimaBeli')
            ->name('retur-beli-process');
        Route::get('/retur/pembelian/cetak/{id}', 'ReturController@cetakTerimaBeli')
            ->name('retur-beli-cetak');

        // Tanda Terima
        Route::get('/tandaterima', 'TandaTerimaController@index')->name('ttr');
        Route::post('/tandaterima/detail/{id}', 'TandaTerimaController@detail')
            ->name('ttr-detail');

        // Cetak Faktur
        Route::get('/cetak-faktur/{status}/{awal}/{akhir}', 'CetakFakturController@index')
            ->name('cetak-faktur');
        Route::post('/cetak-faktur/process', 'CetakFakturController@process')
            ->name('cetak-process');
        Route::get('/cetak/{awal}/{akhir}', 'CetakFakturController@cetak')
            ->name('cetak-all');
        Route::get('/cetak-ttr/{awal}/{akhir}', 'CetakFakturController@tandaterima')
        ->name('cetak-ttr');
        Route::get('/cetak-update/{awal}/{akhir}', 'CetakFakturController@update')
            ->name('cetak-update');
        // Route::post('/cetak-faktur/cetak', 'CetakFakturController@cetak')
            // ->name('cetak-all');

        // Surat Jalan
        Route::get('/sj', 'SuratJalanController@index')->name('sj');
        Route::post('/sj/show', 'SuratJalanController@show')->name('sj-show');
        Route::post('/sj/process/{id}', 'SuratJalanController@process')->name('sj-process');

        // Kartu Stok
        Route::get('kartu', 'KartuStokController@index')->name('kartu');
        Route::post('kartu/show', 'KartuStokController@show')->name('ks-show');
        Route::post('kartu/excel', 'KartuStokController@cetak_excel')->name('ks-excel');

        // Rekap Stok
        Route::get('rekap', 'RekapStokController@index')->name('rekap');
        Route::post('rekap/show', 'RekapStokController@show')->name('rs-show');
        Route::get('rekap/cetak', 'RekapStokController@cetak')->name('rs-cetak');
        Route::post('rekap/pdf', 'RekapStokController@cetak_pdf')->name('rs-pdf');
        Route::post('rekap/excel', 'RekapStokController@cetak_excel')->name('rs-excel');

        // Laporan Keuangan
        Route::get('keuangan', 'LapKeuController@index')->name('lap-keu');
        Route::post('keuangan/show', 'LapKeuController@show')->name('lap-keu-show');
    });

    Route::group(['roles'=>'SUPER'], function() {
        // Approval
        Route::get('approval', 'ApprovalController@index')->name('approval');
        Route::get('approval/show/{id}', 'ApprovalController@show')->name('app-show');
        Route::post('approval/process/{id}', 'ApprovalController@process')->name('app-process');
        Route::post('approval/batal/{id}/{kode}', 'ApprovalController@batal')->name('app-batal');
        Route::get('approval/histori', 'ApprovalController@histori')->name('app-histori');
        Route::get('approval/histori/{id}', 'ApprovalController@detail')->name('app-detail');

        // Master User
        Route::resource('user', 'UserController');

        // Soft Deletes User
        Route::get('user/trash/all', 'UserController@trash')->name('user-trash');
        Route::get('user/restore/{id}', 'UserController@restore')->name('user-restore');
        Route::get('user/restore-all/all', 'UserController@restoreAll')
            ->name('user-restoreAll');
        Route::get('user/hapus/{id}', 'UserController@hapus')->name('user-hapus');
        Route::get('user/hapus-all/all', 'UserController@hapusAll')
            ->name('user-hapusAll');
    });

    Route::group(['roles'=>['ADMIN', 'AR']], function() {
        // Notif
        Route::get('notif', 'NotifController@index')->name('notif');
        Route::get('notif/show/{id}', 'NotifController@show')->name('notif-show');
        Route::get('notif/read/{id}', 'NotifController@markAsRead')->name('notif-read');
    });

    Route::group(['roles'=>['AR', 'SUPER', 'OFFICE02']], function() {
        // Account Receivable
        Route::get('ar', 'AccReceivableController@index')->name('ar');
        Route::post('ar', 'AccReceivableController@index')->name('ar-home');
        Route::post('ar/show', 'AccReceivableController@show')->name('ar-show');
        Route::post('ar/process', 'AccReceivableController@process')->name('ar-process');
    });

    Route::group(['roles'=>['AP', 'SUPER']], function() {
        // Account Payable
        Route::get('ap', 'AccPayableController@index')->name('ap');
        Route::post('ap', 'AccPayableController@index')->name('ap-home');
        Route::post('ap/show', 'AccPayableController@show')->name('ap-show');
        Route::post('ap/detail/{id}', 'AccPayableController@detail')->name('ap-detail');
        Route::post('ap/process', 'AccPayableController@process')->name('ap-process');
        Route::post('ap/transfer', 'AccPayableController@transfer')->name('ap-transfer');
    });

    Route::group(['roles'=>['ADMIN', 'AR', 'SUPER']], function() {
        // Ubah dan Cek Faktur
        Route::get('/so/change', 'SalesOrderController@change')->name('so-change');
        Route::get('/so/change/show', 'SalesOrderController@show')->name('so-show');
    });

    // Route::group(['roles'=>['AR', 'SUPER']], function() {
    //     // Account Receivable
    //     Route::post('ar', 'AccReceivableController@index')->name('ar-home');
    //     Route::post('ar/process', 'AccReceivableController@process')->name('ar-process');
    // });
});

Auth::routes(['verify' => true]);
