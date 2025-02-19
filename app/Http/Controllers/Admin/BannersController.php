<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\Admin\BannerResource;
use App\Http\Resources\Admin\UserResource;
use App\Models\Banner;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderby('id', 'desc')->paginate(10);

        return response()->json(['data' => $banners]);
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'alt_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/banners');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/banners/' . $filename;
            $validatedData['image'] = $imagePath;
        }

        $banner = Banner::create($validatedData);

        return response()->json(['data' => $banner ,'message' => 'banner created successfully'],201);

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
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        return new BannerResource($banner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'alt_text' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/banners');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/banners/' . $filename;
            $validatedData['image'] = $imagePath;
        }

        $banner->update($validatedData);

        return response()->json(['message'=>'banner updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        $banner->delete();

        return response()->json(['message' => 'Banner deleted successfully']);

    }
}
