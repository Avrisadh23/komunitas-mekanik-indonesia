<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Get all products
     */
    public function index()
    {
        return response()->json(Product::active()->get()->toArray());
    }

    /**
     * Create new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->category = $validated['category'] ?? null;
        $product->price = $validated['price'] ?? null;
        $product->is_active = true;  // Set as active by default

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', config('filesystems.uploads'));
            $product->image_path = $path;
        }

        $product->order = (Product::max('order') ?? 0) + 1;
        $product->save();

        return response()->json(['message' => 'Produk berhasil ditambahkan', 'data' => $product->toArray()], 201);
    }

    /**
     * Get single product
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product->toArray());
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->category = $validated['category'] ?? null;
        $product->price = $validated['price'] ?? null;
        $product->is_active = true;  // Ensure stays active on update

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk(config('filesystems.uploads'))->delete(str_replace('storage/', '', $product->image_path));
            }
            $path = $request->file('image')->store('products', config('filesystems.uploads'));
            $product->image_path = $path;
        }

        $product->save();

        return response()->json(['message' => 'Produk berhasil diperbarui', 'data' => $product->toArray()]);
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image_path) {
            Storage::disk(config('filesystems.uploads'))->delete(str_replace('storage/', '', $product->image_path));
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json(['message' => 'Status berhasil diubah', 'data' => $product]);
    }

    /**
     * Update order
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.order' => 'required|integer',
        ]);

        foreach ($validated['products'] as $item) {
            Product::find($item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Urutan produk berhasil diperbarui']);
    }
}
