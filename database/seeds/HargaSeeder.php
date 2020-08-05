<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i=1; $i<=5; $i++) {
            DB::table('harga')->insert([
                'id' => $i,
                'nama' => $faker->word
            ]);
        }
    }
}
