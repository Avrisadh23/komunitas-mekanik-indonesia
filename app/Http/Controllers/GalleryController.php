<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Services\UploadService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Get all galleries
     */
    public function index()
    {
        return response()->json(Gallery::active()->get()->toArray());
    }

    /**
     * Create new gallery
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gallery = new Gallery();
        $gallery->title = $validated['title'];
        $gallery->description = $validated['description'];
        $gallery->is_active = true;  // Set as active by default

        if ($request->hasFile('image')) {
            $gallery->image_path = UploadService::store($request->file('image'), 'galleries');
        }

        $gallery->order = (Gallery::max('order') ?? 0) + 1;
        $gallery->save();

        return response()->json(['message' => 'Gallery berhasil ditambahkan', 'data' => $gallery->toArray()], 201);
    }

    /**
     * Get single gallery
     */
    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery->toArray());
    }

    /**
     * Update gallery
     */
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $gallery->title = $validated['title'];
        $gallery->description = $validated['description'];
        $gallery->is_active = true;  // Ensure stays active on update

        if ($request->hasFile('image')) {
            UploadService::delete($gallery->image_path);
            $gallery->image_path = UploadService::store($request->file('image'), 'galleries');
        }

        $gallery->save();

        return response()->json(['message' => 'Gallery berhasil diperbarui', 'data' => $gallery->toArray()]);
    }

    /**
     * Delete gallery
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        UploadService::delete($gallery->image_path);

        $gallery->delete();

        return response()->json(['message' => 'Gallery berhasil dihapus']);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();

        return response()->json(['message' => 'Status berhasil diubah', 'data' => $gallery]);
    }

    /**
     * Update order
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'galleries' => 'required|array',
            'galleries.*.id' => 'required|exists:galleries,id',
            'galleries.*.order' => 'required|integer',
        ]);

        foreach ($validated['galleries'] as $item) {
            Gallery::find($item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Urutan gallery berhasil diperbarui']);
    }
}
