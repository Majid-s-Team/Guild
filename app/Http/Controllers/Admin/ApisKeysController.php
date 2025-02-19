<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\ApiKey;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApisKeysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keys = ApiKey::paginate(10);

        return response()->json($keys);
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'key' => 'required|string',
            'secret' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = ApiKey::create($validatedData);

        return response()->json(['data' => $data ,'message' => 'banner created successfully'],201);

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
        $data = ApiKey::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'key' => 'required|string',
            'secret' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = ApiKey::find($id);


        if (!$data) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        $data->update($validatedData);

        return response()->json(['message'=>'updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = ApiKey::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        $data->delete();

        return response()->json(['message' => 'data deleted successfully']);

    }
}
