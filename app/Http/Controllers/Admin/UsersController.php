<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\Admin\UserResource;
use App\Http\Responses\ApiErrorResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::userType('admin')->orderby('id', 'desc')->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request): UserResource
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data['user_type'] = 'admin';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/users');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/users/' . $filename;
            $data['image'] = $imagePath;
        }

        $user = User::create($data);
        $user->assignRole($data['role']);

        if ($request->permissions) {
            $user->givePermissionTo($request->permissions);
        }

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/users');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/users/' . $filename;
            $validatedData['image'] = $imagePath;
        }

        $user->update($validatedData);
        $user->syncPermissions($request->permissions);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::findOrFail($id);

        if ($data->super_admin) {
            return new ApiErrorResponse('Denied', '', 403);
        }

        /*if ($data->image) {
            $imagePath = public_path($data->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }*/

        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
