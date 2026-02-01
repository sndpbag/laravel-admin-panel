<?php

namespace Sndpbag\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Parent role relationship for hierarchy.
     */
    public function parent()
    {
        return $this->belongsTo(Role::class, 'parent_id');
    }

    /**
     * Child roles relationship for hierarchy.
     */
    public function children()
    {
        return $this->hasMany(Role::class, 'parent_id');
    }
}
