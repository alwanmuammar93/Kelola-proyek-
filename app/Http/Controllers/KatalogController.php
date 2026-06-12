<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KatalogController extends Controller
{
    /**
     * Display listing of products (Admin)
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('creator');

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        // Order by display_order
        $products = $query->ordered()->paginate(20);

        // Get statistics
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
            'alat_las' => Product::where('category', 'alat-las')->count(),
            'perlengkapan' => Product::where('category', 'Perlengkapan')->count(),
            'perkakas' => Product::where('category', 'perkakas')->count(),
            'cat' => Product::where('category', 'Cat')->count(),
        ];

        $categories = Product::getCategories();
        $ecommercePlatforms = Product::getEcommercePlatforms();

        return view('admin.katalog.index', compact('products', 'stats', 'categories', 'ecommercePlatforms'));
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:alat-las,Perlengkapan,perkakas,Cat',
            'ecommerce' => 'required|in:shopee,tokopedia,tiktok,facebook,whatsapp',
            'link' => 'required|url|max:1000',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Upload image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            // Get max display_order
            $maxOrder = Product::max('display_order') ?? 0;

            // Create product
            $product = Product::create([
                'name' => $request->name,
                'category' => $request->category,
                'ecommerce' => $request->ecommerce,
                'link' => $request->link,
                'description' => $request->description,
                'image' => $imagePath,
                'status' => $request->status ?? 'active',
                'display_order' => $maxOrder + 1,
                'created_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Produk berhasil ditambahkan!',
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product for editing
     */
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'success' => true,
                'product' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan!'
            ], 404);
        }
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:alat-las,Perlengkapan,perkakas,Cat',
            'ecommerce' => 'required|in:shopee,tokopedia,tiktok,facebook,whatsapp',
            'link' => 'required|url|max:1000',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);

            // Prepare update data
            $updateData = [
                'name' => $request->name,
                'category' => $request->category,
                'ecommerce' => $request->ecommerce,
                'link' => $request->link,
                'description' => $request->description,
                'status' => $request->status ?? 'active',
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                $product->deleteImage();
                
                // Upload new image
                $updateData['image'] = $request->file('image')->store('products', 'public');
            }

            // Update product
            $product->update($updateData);

            return response()->json([
                'success' => true,
                'message' => '✅ Produk berhasil diperbarui!',
                'product' => $product->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            // Delete image
            $product->deleteImage();

            // Delete product
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => '✅ Produk berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder products (drag & drop)
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:products,id_product',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid!'
            ], 422);
        }

        try {
            // Update display_order untuk setiap produk
            foreach ($request->order as $index => $productId) {
                Product::where('id_product', $productId)
                    ->update(['display_order' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Urutan produk berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle product status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);

            $newStatus = $product->status === 'active' ? 'inactive' : 'active';
            $product->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => '✅ Status produk berhasil diubah!',
                'status' => $newStatus
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}