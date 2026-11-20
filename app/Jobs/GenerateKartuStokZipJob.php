<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Exception;

class GenerateKartuStokZipJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300;

    protected $exportId;

    public function __construct($exportId)
    {
        $this->exportId = $exportId;
    }

    public function handle()
    {
        $directory = "exports/kartu-stok/{$this->exportId}";

        $disk = Storage::disk('local');

        $files = $disk->files($directory);

        if (empty($files)) {
            throw new Exception('Tidak ditemukan file Excel.');
        }

        $zipDirectory = 'exports/kartu-stok';

        $disk->makeDirectory($zipDirectory);

        $zipRelativePath = "{$zipDirectory}/{$this->exportId}.zip";

        $zipPath = $disk->path($zipRelativePath);

        $zip = new ZipArchive();

        if ($zip->open(
            $zipPath,
            ZipArchive::CREATE | ZipArchive::OVERWRITE
        ) !== true) {
            throw new Exception('Gagal membuat ZIP.');
        }

        foreach ($files as $file) {
            $zip->addFile(
                $disk->path($file),
                basename($file)
            );
        }

        $zip->close();

        // Update status export.
        DB::table('stock_card_exports')
            ->where('id', $this->exportId)
            ->update([
                'status' => 'completed',
                'zip_path' => $zipRelativePath,
                'updated_at' => now(),
            ]);

        // Hapus temporary Excel.
        $disk->deleteDirectory($directory);
    }

    public function failed(Exception $exception)
    {
        DB::table('stock_card_exports')
            ->where('id', $this->exportId)
            ->update([
                'status' => 'failed',
                'updated_at' => now(),
            ]);
    }
}