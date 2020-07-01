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
            'userRoleId' => (string) \Hashids::connection(\App\Role::class)->encode($role->id),
            'userRole' => (string) $role->role,
            'creationDate' => (string) $role->created_at,
            'lastChange' => (string) $role->updated_at,
            'deletedDate' => isset($role->deleted_at) ? (string) $role->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('roles.show', \Hashids::connection(\App\Role::class)->encode($role->id)),
                ],
                [
                    'rel' => 'roles.users',
                    'href' => route('roles.users.index', \Hashids::connection(\App\Role::class)->encode($role->id)),
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
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
