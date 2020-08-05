<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i=1; $i<=10; $i++) {
            DB::table('barang')->insert([
                'id' => $i,
                'nama' => $faker->word,
                'ukuran'=> $faker->numberBetween($min = 1, $max = 10),
                'isi' => $faker->numberBetween($min = 1, $max = 100)
            ]);
        }
    }
}
