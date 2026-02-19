<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BengkelController extends Controller
{
    /**
     * Get all bengkels
     */
    public function index()
    {
        return response()->json(Bengkel::active()->get());
    }

    /**
     * Get bengkels by province
     */
    public function byProvince($province)
    {
        $bengkels = Bengkel::active()->byProvince($province)->get();
        return response()->json($bengkels);
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        $provinces = Bengkel::getProvinces();
        return response()->json($provinces);
    }

    /**
     * Get cities by province
     */
    public function getCitiesByProvince($province)
    {
        $cities = Bengkel::getCitiesByProvince($province);
        return response()->json($cities);
    }

    /**
     * Create new bengkel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $bengkel = new Bengkel();
        $bengkel->name = $validated['name'];
        $bengkel->owner = $validated['owner'];
        $bengkel->address = $validated['address'];
        $bengkel->city = $validated['city'];
        $bengkel->province = $validated['province'];
        $bengkel->phone = $validated['phone'];
        $bengkel->email = $validated['email'] ?? null;
        $bengkel->description = $validated['description'] ?? null;
        $bengkel->latitude = $validated['latitude'] ?? null;
        $bengkel->longitude = $validated['longitude'] ?? null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('bengkels', 'public');
            $bengkel->image_path = 'storage/' . $path;
        }

        $bengkel->save();

        return response()->json(['message' => 'Bengkel berhasil ditambahkan', 'data' => $bengkel], 201);
    }

    /**
     * Get single bengkel
     */
    public function show($id)
    {
        $bengkel = Bengkel::findOrFail($id);
        return response()->json($bengkel);
    }

    /**
     * Update bengkel
     */
    public function update(Request $request, $id)
    {
        $bengkel = Bengkel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $bengkel->name = $validated['name'];
        $bengkel->owner = $validated['owner'];
        $bengkel->address = $validated['address'];
        $bengkel->city = $validated['city'];
        $bengkel->province = $validated['province'];
        $bengkel->phone = $validated['phone'];
        $bengkel->email = $validated['email'] ?? null;
        $bengkel->description = $validated['description'] ?? null;
        $bengkel->latitude = $validated['latitude'] ?? null;
        $bengkel->longitude = $validated['longitude'] ?? null;

        if ($request->hasFile('image')) {
            if ($bengkel->image_path) {
                Storage::disk('public')->delete(str_replace('storage/', '', $bengkel->image_path));
            }
            $path = $request->file('image')->store('bengkels', 'public');
            $bengkel->image_path = 'storage/' . $path;
        }

        $bengkel->save();

        return response()->json(['message' => 'Bengkel berhasil diperbarui', 'data' => $bengkel]);
    }

    /**
     * Delete bengkel
     */
    public function destroy($id)
    {
        $bengkel = Bengkel::findOrFail($id);

        if ($bengkel->image_path) {
            Storage::disk('public')->delete(str_replace('storage/', '', $bengkel->image_path));
        }

        $bengkel->delete();

        return response()->json(['message' => 'Bengkel berhasil dihapus']);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $bengkel = Bengkel::findOrFail($id);
        $bengkel->is_active = !$bengkel->is_active;
        $bengkel->save();

        return response()->json(['message' => 'Status berhasil diubah', 'data' => $bengkel]);
    }
}
