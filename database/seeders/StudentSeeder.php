<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $faker = Faker::create();

        foreach(range(1,5) as $index){

            Student::create([

                'student_name'=>$faker->name,
                'standard'=>Str::random(10),
            ]);

        }
    }
}
