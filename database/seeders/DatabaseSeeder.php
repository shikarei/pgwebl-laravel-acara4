<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'nadinezahida',
            'email' => 'nadinezahidafaadhilah@mail.ugm.ac.id',
            'password' => bcrypt('7027Dothebest'),
        ]);
        User::factory()->create([
            'name' => 'heizou',
            'email' => 'thegreatestdetective@gmail.com',
            'password' => bcrypt('shikanoinheizou'),
        ]);
        User::factory()->create([
            'name' => 'asaba',
            'email' => 'tiredofwork@gmail.com',
            'password' => bcrypt('harumasaneedbreak'),
        ]);
    }
}
