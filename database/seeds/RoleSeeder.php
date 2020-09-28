<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Truncate the user table
        Role::truncate();

        // Clear the user events
        Role::flushEventListeners();

        // Add roles
        factory(Role::class)->create(['role' => 'SuperAdmin']);
        factory(Role::class)->create(['role' => 'Admin']);
        factory(Role::class)->create(['role' => 'User']);
    }
}
