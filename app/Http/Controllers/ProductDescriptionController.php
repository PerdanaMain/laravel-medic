<?php
// app/Http/Controllers/ProductDescriptionController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductDescriptionController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories.images'])->get();
        return view('index', compact('products'));
    }

    public function store(Request $request)
    {
        \Log::info('Request received', $request->all());
        \Log::info('Files received', $request->allFiles());

        $validator = Validator::make($request->all(), [
            'products'                            => 'required|array|max:5',
            'products.*.name'                     => 'required|string|max:255',
            'products.*.categories'               => 'nullable|array|max:3',
            'products.*.categories.*.description' => 'required|string',
            'products.*.categories.*.images.*'    => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $savedProducts = [];

        foreach ($request->products as $productData) {
            // Skip empty products
            if (empty($productData['name'])) {
                continue;
            }

            \Log::info('Processing product', ['name' => $productData['name']]);

            $product = Product::create([
                'name' => $productData['name'],
            ]);

            // Process categories if they exist
            if (isset($productData['categories']) && is_array($productData['categories'])) {
                foreach ($productData['categories'] as $categoryData) {
                    // Skip empty categories
                    if (empty($categoryData['description'])) {
                        continue;
                    }

                    \Log::info('Processing category', ['description' => $categoryData['description']]);

                    $category = $product->categories()->create([
                        'description' => $categoryData['description'],
                    ]);

                    // Process images for this category
                    if (isset($categoryData['images']) && is_array($categoryData['images'])) {
                        foreach ($categoryData['images'] as $image) {
                            if ($image && $image->isValid()) {
                                $path = $image->store('product-images', 'public');
                                \Log::info('Image stored', ['path' => $path]);

                                $category->images()->create([
                                    'path' => $path,
                                ]);
                            } else {
                                \Log::warning('Invalid image found');
                            }
                        }
                    }
                }
            }

            $savedProducts[] = $product->load('categories.images');
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil disimpan',
            'data'    => $savedProducts,
        ]);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Delete all associated images from storage
        foreach ($product->categories as $category) {
            foreach ($category->images as $image) {
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
            }
        }

        $product->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Produk berhasil dihapus',
        ]);
    }

    public function deleteCategory($id)
    {
        $category = ProductCategory::findOrFail($id);

        // Delete all associated images from storage
        foreach ($category->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $category->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil dihapus',
        ]);
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Gambar berhasil dihapus',
        ]);
    }
}
