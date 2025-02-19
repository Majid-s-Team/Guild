<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
   public function index()
   {
       $permissions = Permission::get()->pluck('name')->toarray();
       $result = [];
       foreach ($permissions as $permission) {
           [$parent, $child] = explode('.', $permission, 2);
           if (!isset($result[$parent])) {
               $result[$parent] = [];
           }
           $result[$parent][] = $permission;
       }

       return new ApiSuccessResponse($result);

   }
    public function store(Request $request)
    {
        $permission = Permission::create(['name' => $request->name]);

        return new ApiSuccessResponse($permission, 'permission created successfully!',[],201);

    }
}
