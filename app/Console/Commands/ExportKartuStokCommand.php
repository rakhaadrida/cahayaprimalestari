<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ExportKartuStokJob;
use App\Models\Barang;

class ExportKartuStokCommand extends Command
{
    protected $signature = 'kartu-stok:export
        {awal}
        {akhir}
        {--kode-awal=}
        {--kode-akhir=}
        {--cabang=}';

    protected $description = 'Export kartu stok seluruh produk ke Excel melalui queue';

    public function handle()
    {
        $awal = $this->argument('awal');
        $akhir = $this->argument('akhir');

        $kodeAwal = $this->option('kode-awal');
        $kodeAkhir = $this->option('kode-akhir');
        $cabang = $this->option('cabang');


        $products = Barang::query()
            ->where('id_kategori', 'KAT01')
            ->orderBy('id')
            ->pluck('id');

        if ($products->isEmpty()) {
            $this->error('Tidak ada produk ditemukan.');

            return 1;
        }

        foreach ($products as $kodeProduk) {
            ExportKartuStokJob::dispatch(
                $awal,
                $akhir,
                $kodeProduk,
                $cabang
            )->onQueue('exports');
        }

        $this->info('Export berhasil dimasukkan ke queue!');
        $this->info('Total produk: ' . $products->count());

        return 0;
    }
}