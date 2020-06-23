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
        factory(User::class)->create(['name' => 'Vino', 'email' => 'vino@ab.com', 'password' => '$2y$10$1KeHVDk4jl1LU7psksi9iet369iU7eli4fshl/zXcIxQqKCLMTZSW','image' => 'default.jpg', 'verified' => 1, 'admin' => User::ADMIN_USER, 'verification_token' => null]);
    }
}
