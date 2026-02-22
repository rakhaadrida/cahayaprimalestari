<?php

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = [
            [
                'name' => 'Utama',
            ],
            [
                'name' => 'Kenari',
            ],
            [
                'name' => 'Cianjur',
            ],
        ];

        foreach ($branches as $branch) {
            Cabang::create([
                'nama' => $branch['name'],
            ]);
        }
    }
}
