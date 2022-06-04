<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            "name" => "Paulo Teodoro",
            "email" => 'pauloteodoroti@gmail.com'
        ]);

        User::factory()
                ->count(5)
                ->create();
    }
}
