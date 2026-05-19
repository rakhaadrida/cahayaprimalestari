<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\StokBarang;
use Exception;
use Illuminate\Console\Command;

class CreateProductStockStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:product-stock-store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command for create product stock store.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $products = Barang::query()
            ->whereNull('deleted_at')
            ->get();

        $warehouse = Gudang::query()->find('GDG10');

        foreach($products as $product) {
            StokBarang::create([
                'id_barang' => $product->id,
                'id_gudang' => $warehouse->id,
                'stok' => 100,
                'status' => 'T'
            ]);
        }

        return 0;
    }
}
