<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\ApiController;
use App\Role;

class RoleUserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        $users = $role->users;

        return $this->showAll($users);
    }
}
