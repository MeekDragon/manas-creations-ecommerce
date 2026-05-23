<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.product-form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['images'] = $this->handleImages($request);
        $data['is_active'] = $request->has('is_active');

        $pricing = [
            'mrp' => $data['mrp'],
            'discount' => $data['discount'],
            'stock' => $data['stock']
        ];
        
        unset($data['mrp'], $data['discount'], $data['stock']);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $pricing) {
            $product = Product::create($data);
            $product->variants()->create([
                'size' => 'Standard',
                'mrp' => $pricing['mrp'],
                'discount' => $pricing['discount'],
                'stock' => $pricing['stock'],
            ]);
        });

        return redirect()->route('admin.products')->with('success', 'Product added!');
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->ordered()->get();
        // Load standard variant attributes to prefill form fields if they exist
        $variant = $product->variants()->where('size', 'Standard')->first();
        if ($variant) {
            $product->mrp = $variant->mrp;
            $product->discount = $variant->discount;
            $product->stock = $variant->stock;
        } else {
            $product->mrp = 0;
            $product->discount = 0;
            $product->stock = 0;
        }
        return view('admin.product-form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);
        $data['is_active'] = $request->has('is_active');

        // Merge new uploads with any kept existing images
        $existing = $request->input('existing_images', []);
        $newImages = $this->handleImages($request);
        $data['images'] = array_merge($existing, $newImages);

        $pricing = [
            'mrp' => $data['mrp'],
            'discount' => $data['discount'],
            'stock' => $data['stock']
        ];
        
        unset($data['mrp'], $data['discount'], $data['stock']);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $pricing, $product) {
            $product->update($data);
            
            $variant = $product->variants()->where('size', 'Standard')->first();
            if ($variant) {
                $variant->update([
                    'mrp' => $pricing['mrp'],
                    'discount' => $pricing['discount'],
                    'stock' => $pricing['stock'],
                ]);
            } else {
                $product->variants()->delete();
                $product->variants()->create([
                    'size' => 'Standard',
                    'mrp' => $pricing['mrp'],
                    'discount' => $pricing['discount'],
                    'stock' => $pricing['stock'],
                ]);
            }
        });

        return redirect()->route('admin.products')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        // Keep image files on disk during soft-delete so they can be restored later
        $product->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.products')->with('success', 'Product moved to trash.');
    }

    public function restore($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Product restored successfully.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        
        foreach (($product->images ?? []) as $img) {
            if (str_starts_with($img, 'products/')) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->forceDelete();
        return back()->with('success', 'Product permanently deleted.');
    }

    /**
     * AJAX: upload a single image, return its public URL.
     * Used by the image upload widget in the product form.
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate(['image' => 'required|image|max:5120']);
            $file = $request->file('image');

            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');

            if (!empty($supabaseUrl) && !empty($supabaseKey)) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $uploadUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/products/' . $filename;

                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $supabaseKey,
                    'apikey' => $supabaseKey,
                    'Content-Type' => $file->getClientMimeType(),
                ])->withBody(
                    file_get_contents($file->getRealPath()),
                    $file->getClientMimeType()
                )->post($uploadUrl);

                if ($response->successful()) {
                    $publicUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/products/' . $filename;
                    return response()->json([
                        'path' => $publicUrl,
                        'url'  => $publicUrl,
                    ]);
                }

                $errMsg = $response->json('message') ?? $response->body();
                \Illuminate\Support\Facades\Log::error('Supabase Storage upload failed: ' . $response->body());
                return response()->json([
                    'error' => 'Supabase Upload Error: ' . $errMsg
                ], 400);
            }

            // Fallback to local storage (only when Supabase credentials are not set)
            $path = $file->store('products', 'public');

            return response()->json([
                'path' => $path,
                'url'  => Storage::disk('public')->url($path),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Image upload exception: ' . $e->getMessage());
            return response()->json([
                'error' => 'Upload Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // ── Helpers ──────────────────────────────────
    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tagline'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mrp'         => 'required|numeric|min:0',
            'discount'    => 'required|integer|min:0|max:100',
            'stock'       => 'required|integer|min:0',
        ]);
    }

    /**
     * Handle uploaded image files and return an array of storage paths.
     */
    private function handleImages(Request $request): array
    {
        $paths = $request->input('images_uploaded', []);
        
        // Also support traditional file upload fallback just in case
        if ($request->hasFile('images')) {
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');

            foreach ($request->file('images') as $file) {
                if (!empty($supabaseUrl) && !empty($supabaseKey)) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $uploadUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/products/' . $filename;

                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => 'Bearer ' . $supabaseKey,
                        'apikey' => $supabaseKey,
                        'Content-Type' => $file->getClientMimeType(),
                    ])->withBody(
                        file_get_contents($file->getRealPath()),
                        $file->getClientMimeType()
                    )->post($uploadUrl);

                    if ($response->successful()) {
                        $paths[] = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/products/' . $filename;
                        continue;
                    }
                    \Illuminate\Support\Facades\Log::error('Supabase Storage upload fallback failed: ' . $response->body());
                }

                // Local fallback
                $paths[] = $file->store('products', 'public');
            }
        }
        return $paths;
    }
}
