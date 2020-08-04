<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i=1; $i<20; $i++) {
            DB::table('supplier')->insert([
                'id' => $i,
                'nama' => $faker->company,
                'alamat' => $faker->address,
                'telepon' => $faker->phoneNumber
            ]);
        }
    }
}
