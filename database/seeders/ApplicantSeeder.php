<?php

namespace Database\Seeders;

use App\Models\Applicant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// Pakai Faker supaya banyak data dummy
// use Faker\Factory as faker;

class ApplicantSeeder extends Seeder
{
    public function run(): void
    {

        // // Masukin ke variabel fakernya dengan format Indonesia ('id_ID')
        // $faker = Faker::create('id_ID');
        
        // // Looping biar banyak, ini 25
        // for ($i=0; $i < 25; $i++) { 
        //     Applicant::create([
        //         'name' => $faker->name(),
        //         dst..
        //         'name' => 'Budi Santoso',
        //         'email' => 'budi@gmail.com',
        //         'status' => 'Pending',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // };

        DB::table('applicants')->insert([
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@gmail.com',
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Rahma',
                'email' => 'siti@gmail.com',
                'status' => 'Reviewed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dimas Pratama',
                'email' => 'dimas@gmail.com',
                'status' => 'Accepted',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
