<?php

use App\User;
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Truncate the user table
        User::truncate();

        // Clear the user events
        User::flushEventListeners();

        factory(User::class)->create(['name' => 'Vino', 'email' => 'vino@ab.com', 'password' => '$2y$10$1KeHVDk4jl1LU7psksi9iet369iU7eli4fshl/zXcIxQqKCLMTZSW', 'image' => 'default.jpeg', 'verified' => 1, 'admin' => User::ADMIN_USER, 'verification_token' => null]);

        $usersQunatity = 1000;
        factory(User::class, $usersQunatity)->create();
    }
}
