<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * Roles
 */

Route::resource('roles', 'Role\RoleController', ['except' => ['create', 'edit']]);

Route::name('rolesList')->get('rolesList', 'Role\RoleController@list');

Route::resource('roles.users', 'Role\RoleUserController', ['only' => ['index']]);

/**
 * Users
 */

Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);

Route::resource('users.roles', 'User\UserRoleController', ['only' => ['index']]);

Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify');

Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
