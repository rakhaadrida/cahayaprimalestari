<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i=1; $i<=3; $i++) {
            DB::table('gudang')->insert([
                'id' => $i,
                'nama' => $faker->streetName,
                'alamat' => $faker->address
            ]);
        }
    }
}
