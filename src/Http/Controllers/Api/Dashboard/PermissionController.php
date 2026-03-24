<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Api\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Sndpbag\AdminPanel\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy('group_name');

        return response()->json([
            'status' => 'success',
            'data' => $permissions
        ]);
    }

    public function show(Permission $permission)
    {
        return response()->json([
            'status' => 'success',
            'permission' => $permission
        ]);
    }
}
