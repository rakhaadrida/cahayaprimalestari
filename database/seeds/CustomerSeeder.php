<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
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
            DB::table('customer')->insert([
                'id' => $i,
                'nama' => $faker->company,
                'alamat' => $faker->address,
                'telepon' => $faker->phoneNumber,
                'contact_person' => $faker->firstName,
                'tempo' => $faker->word,
                'limit' => $faker->randomDigit,
                'sales_cover' => $faker->word
            ]);
        }
    }
}
