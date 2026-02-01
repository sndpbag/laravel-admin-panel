<?php

namespace Sndpbag\AdminPanel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    /**
     * The users that belong to the permission (Direct permissions).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'permission_user');
    }
}
