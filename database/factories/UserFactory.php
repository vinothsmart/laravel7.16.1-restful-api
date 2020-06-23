<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'image' => 'default.jpg', 
        'verified' => User::UNVERIFIED_USER,
        'verification_token' => null,
        'admin' => User::REGULAR_USER,
    ];
});

$factory->afterCreating(User::class, function ($user, $faker) {
    /**
     * Role assign for user
     */
    if($user->id <= 1){
        $userRoleAssign = [
            'role_id' => 1,
            'user_id' => $user->id,
        ];
    } else {
        $userRoleAssign = [
            'role_id' => Role::where('id', '>', 1)->get()->random()->id,
            'user_id' => $user->id,
        ];
    }
    DB::table('roles_users')->insert($userRoleAssign);

    /**
     * Getting role information for user
     */
    $role = DB::table('roles_users')->where('user_id', $user->id)->first();

    /**
     * Updating User details based on role
     */
    $roleId = $role->role_id;

    if($roleId == 1 || $roleId == 2) {
        $isAdmin = true;
    } else {
        $isAdmin = false;
    }

    $updateUser = User::findOrFail($user->id);
    $updateUser->email_verified_at =  $isAdmin == true ? now() : null;
    $updateUser->verified = $isAdmin == true ? User::VERIFIED_USER : User::UNVERIFIED_USER;
    $updateUser->verification_token = $isAdmin == true ? null : User::generateVerificationCode();
    $updateUser->admin = $isAdmin == true ? User::ADMIN_USER : User::REGULAR_USER;
    $updateUser->save();
});