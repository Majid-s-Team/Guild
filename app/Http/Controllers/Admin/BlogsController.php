<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Blog::orderby('id', 'desc')->paginate(10);

        return response()->json(['data' => $data]);
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
    public function store(BlogRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $validatedData['user_id'] = 1;//Auth::user()->id;
        $validatedData['is_active'] = 1;
        //$scheduleDatetime = null;

        if (!empty($validatedData['schedule_date']) && !empty($validatedData['schedule_time'])) {
            $validatedData['schedule_date'] = Carbon::parse("{$validatedData['schedule_date']} {$validatedData['schedule_time']}");
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/blogs');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/blogs/' . $filename;
            $validatedData['image'] = $imagePath;
        }

        $blog = Blog::create($validatedData);

        return response()->json(['data' => $blog], 201);

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
        $data = Blog::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'is_active' => 'required|boolean',
            'page_excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data = Blog::find($id);

        if (!$data) {
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
        } else {
            $validatedData['image'] = $data->image;
        }

        $data->update($validatedData);

        return response()->json(['message'=>'blog updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Blog::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        $data->delete();

        return response()->json(['message' => 'blog deleted successfully']);

    }
}
