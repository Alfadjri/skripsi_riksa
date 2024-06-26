<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(Roles::class);
        $this->call(UserSeeder::class);
        $this->call(TestSeeder::class);
        $this->call(summary::class);
        $this->call(soal_sementara::class);
    }
}
