<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\ApiController;
use App\Role;
use App\Transformers\RoleTransformer;
use Illuminate\Http\Request;

class RoleController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . RoleTransformer::class)->only(['store', 'update']);

        $this->middleware('client.credentials')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return $this->showAll($roles);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function list() {
        $roles = Role::select('id', 'role')->get();

        return $this->showList($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $rules = [
            'role' => 'required',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['role'] = $request->role;
        $data['client_details'] = $this->applicationDetector();

        $role = Role::create($data);

        return $this->showOne($role, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return $this->showOne($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        // Validation
        $rules = [
            'role' => 'required',
        ];

        $this->validate($request, $rules);

        if ($request->has('role')) {
            $role->role = $request->role;
        }

        if (!$role->isDirty()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $role->client_details = $this->applicationDetector();

        $role->save();

        return $this->showOne($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $this->showOne($role);
    }
}
