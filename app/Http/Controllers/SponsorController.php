<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    /**
     * Get all sponsors
     */
    public function index()
    {
        return response()->json(Sponsor::active()->get()->toArray());
    }

    /**
     * Create new sponsor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
        ]);

        $sponsor = new Sponsor();
        $sponsor->name = $validated['name'];
        $sponsor->description = $validated['description'] ?? null;
        $sponsor->url = $validated['url'] ?? null;
        $sponsor->is_active = true;  // Set as active by default

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('sponsors', config('filesystems.uploads'));
            $sponsor->logo_path = $path;
        }

        $sponsor->order = (Sponsor::max('order') ?? 0) + 1;
        $sponsor->save();

        return response()->json(['message' => 'Sponsor berhasil ditambahkan', 'data' => $sponsor->toArray()], 201);
    }

    /**
     * Get single sponsor
     */
    public function show($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        return response()->json($sponsor->toArray());
    }

    /**
     * Update sponsor
     */
    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'url' => 'nullable|url',
        ]);

        $sponsor->name = $validated['name'];
        $sponsor->description = $validated['description'] ?? null;
        $sponsor->url = $validated['url'] ?? null;
        $sponsor->is_active = true;  // Ensure stays active on update

        if ($request->hasFile('logo')) {
            if ($sponsor->logo_path) {
                Storage::disk(config('filesystems.uploads'))->delete(str_replace('storage/', '', $sponsor->logo_path));
            }
            $path = $request->file('logo')->store('sponsors', config('filesystems.uploads'));
            $sponsor->logo_path = $path;
        }

        $sponsor->save();

        return response()->json(['message' => 'Sponsor berhasil diperbarui', 'data' => $sponsor->toArray()]);
    }

    /**
     * Delete sponsor
     */
    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);

        if ($sponsor->logo_path) {
            Storage::disk(config('filesystems.uploads'))->delete(str_replace('storage/', '', $sponsor->logo_path));
        }

        $sponsor->delete();

        return response()->json(['message' => 'Sponsor berhasil dihapus']);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->is_active = !$sponsor->is_active;
        $sponsor->save();

        return response()->json(['message' => 'Status berhasil diubah', 'data' => $sponsor]);
    }

    /**
     * Update order
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'sponsors' => 'required|array',
            'sponsors.*.id' => 'required|exists:sponsors,id',
            'sponsors.*.order' => 'required|integer',
        ]);

        foreach ($validated['sponsors'] as $item) {
            Sponsor::find($item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Urutan sponsor berhasil diperbarui']);
    }
}
