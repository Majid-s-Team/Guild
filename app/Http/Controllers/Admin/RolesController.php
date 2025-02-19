<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::get();

        return new ApiSuccessResponse($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            //'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->permissions) {
            $role->givePermissionTo($request->permissions);
        }

        return new ApiSuccessResponse($role,'role created successfully!',[],201);
    }

    public function destroy(string $id)
    {
        $banner = Role::find($id);

        if (!$banner) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        $banner->delete();

        return response()->json(['message' => 'Role deleted successfully']);

    }

    /*public function assignRole(Request $request){

        $user = User::findOrFail($request->user_id);

        $user->assignRole($request->role);

        return new ApiSuccessResponse($user,['message' => 'role assign successfully!'],201);
    }*/

}
