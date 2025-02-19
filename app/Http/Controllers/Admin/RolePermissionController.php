<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function assignPermissionsToRole(Request $request, Role $role): ApiSuccessResponse
    {
        $this->validate($request, [
            'permissions' => ['required', 'array'],
            'permissions.*' => 'required'
        ]);

        $role->syncPermissions($request->permissions);

        return new ApiSuccessResponse($role,['message' => 'Permissions assigned successfully'],201);

    }
}
