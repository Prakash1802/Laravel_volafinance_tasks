<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\Mark;

class MarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
       $faker = Faker::create();

        foreach(range(1,10) as $index){

            Mark::create([

                'student_id'=>Student::all()->random()->id,
                'subject_name'=>Str::random(10),
                'marks'=>rand(10,100),
                'test_date'=>$faker->dateTime()->format('Y-m-d'),
            ]);

        }
    }
}
