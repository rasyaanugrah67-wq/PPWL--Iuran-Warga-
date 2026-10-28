<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Iuran;
use Faker\Factory as Faker;

class IuranSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i = 1; $i <= 20; $i++){
            Iuran::insert([
                // Asumsi id_warga dan id_periode 1-5 sudah ada di database
                'id_warga' => $faker->numberBetween(1, 5), 
                'id_periode' => 1,
                'nominal' => $faker->randomElement([50000, 100000, 150000]),
                'jatuh_tempo' => $faker->date('Y-m-d', 'now'),
                'status' => $faker->randomElement(['Lunas', 'Menunggu']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
