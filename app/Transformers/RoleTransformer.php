<?php

namespace App\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'userRoleId' => (int) $role->id,
            'userRole' => (string) $role->role,
            'creationDate' => (string) $role->created_at,
            'lastChange' => (string) $role->updated_at,
            'clientDetails' => (string) $role->client_details,
            'deletedDate' => isset($role->deleted_at) ? (string) $role->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('roles.show', $role->id),
                ],
                [
                    'rel' => 'roles.users',
                    'href' => route('roles.users.index', $role->id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'userRoleId' => 'id',
            'userRole' => 'role',
            'creationDate' => 'created_at',
            'lastChange' => 'updated_at',
            'deletedDate' => 'deleted_at',
            'clientDetails' => 'client_details',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id' => 'userRoleId',
            'role' => 'userRole',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            'updated_at' => 'deletedDate',
            'client_details' => 'clientDetails',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
