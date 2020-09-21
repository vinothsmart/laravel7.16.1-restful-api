<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\ApiController;
use App\Role;

class RoleUserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);

        $this->middleware('scope:read-general')->only(['index']);

        // $this->middleware('can:view,role')->only(['index']);
    }

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
