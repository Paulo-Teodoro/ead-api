<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::factory()
                ->has(
                    Module::factory()
                            ->has(
                                Lesson::factory()
                                    ->count(10)
                            )
                            ->count(5)
                )
                ->count(3)
                ->create();
    }
}
