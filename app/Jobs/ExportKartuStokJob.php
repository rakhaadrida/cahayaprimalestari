<?php

namespace App\Jobs;

use App\Exports\KartuStokExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportKartuStokJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    protected $awal;
    protected $akhir;
    protected $kodeProduk;
    protected $cabang;

    public function __construct($awal, $akhir, $kodeProduk, $cabang)
    {
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->kodeProduk = $kodeProduk;
        $this->cabang = $cabang;
    }

    public function handle()
    {
        file_put_contents(
            storage_path('queue-debug.txt'),
            "START: {$this->kodeProduk}" . PHP_EOL,
            FILE_APPEND
        );

        $filename = "exports/kartu-stok/{$this->kodeProduk}.xlsx";

        $result = Excel::store(
            new KartuStokExport(
                $this->awal,
                $this->akhir,
                $this->kodeProduk,
                $this->kodeProduk,
                $this->cabang
            ),
            $filename,
            'local'
        );

        file_put_contents(
            storage_path('queue-debug.txt'),
            "FINISH: {$this->kodeProduk} | Result: " .
            var_export($result, true) . PHP_EOL,
            FILE_APPEND
        );
    }
}