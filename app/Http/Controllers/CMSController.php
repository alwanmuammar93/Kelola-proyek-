<?php

namespace App\Http\Controllers;

use App\Models\CMSSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CMSController extends Controller
{
    public function index()
    {
        $hero = CMSSection::getByKey('hero_beranda');
        $about = CMSSection::getByKey('about_company');
        $services = CMSSection::getByKey('services_title');
        $catalog = CMSSection::getByKey('catalog_hero');
        $proyek = CMSSection::getByKey('proyek_hero');
        
        if (!$hero) {
            $hero = CMSSection::create([
                'section_key' => 'hero_beranda',
                'title' => 'PT SURABAYA LAS',
                'subtitle' => 'Solusi Konstruksi & Penjualan|Alat dan Bahan Bangunan|Terbaik Untuk Anda',
                'image' => 'images/WhatsApp Image 2025-11-23 at 00.20.09_da1f500c.jpg',
                'logo' => 'images/SURABAYA_LAS_INTI-removebg-preview.png',
                'button1_text' => 'LIHAT PROYEK KAMI',
                'button1_link' => '/galeri-proyek',
                'button2_text' => 'HUBUNGI KAMI SEKARANG',
                'button2_link' => '/kontak',
                'is_active' => true,
            ]);
        }
        
        if (!$about) {
            $about = CMSSection::create([
                'section_key' => 'about_company',
                'title' => 'PT SURABAYA LAS',
                'content' => 'Kami merupakan spesialis jasa bengkel las profesional yang telah berpengalaman puluhan tahun dibidangnya. Komitmen kami adalah memberikan layanan terbaik dalam pekerjaan dan konstruksi pengelasan dengan harga yang kompetitif.',
                'is_active' => true,
            ]);
        }
        
        if (!$services) {
            $services = CMSSection::create([
                'section_key' => 'services_title',
                'title' => 'Layanan Unggulan Kami',
                'button1_text' => 'Pelajari Lebih Lanjut',
                'button1_link' => '/tentang-kami',
                'is_active' => true,
            ]);
        }

        if (!$catalog) {
            $catalog = CMSSection::create([
                'section_key' => 'catalog_hero',
                'title' => 'KATALOG PRODUK',
                'subtitle' => 'Jelajahi berbagai produk berkualitas dari PT Surabaya Las',
                'background_image' => 'images/PRESENTATION (1).png',
                'is_active' => true,
            ]);
        }

        if (!$proyek) {
            $proyek = CMSSection::create([
                'section_key' => 'proyek_hero',
                'title' => 'LAYANAN KONSTRUKSI',
                'subtitle' => 'PT SURABAYA LAS',
                'background_image' => 'images/proyek/asasasaasa.jpg',
                'button1_text' => 'HUBUNGI KAMI SEKARANG',
                'button1_link' => 'https://wa.me/6285211887779',
                'is_active' => true,
            ]);
        }
        
        return view('admin.cms.dashboard', compact('hero', 'about', 'services', 'catalog', 'proyek'));
    }

    public function heroUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle_line1' => 'required|string|max:255',
            'subtitle_line2' => 'required|string|max:255',
            'subtitle_line3' => 'required|string|max:255',
            'button1_text' => 'nullable|string|max:100',
            'button1_link' => 'nullable|string|max:255',
            'button2_text' => 'nullable|string|max:100',
            'button2_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $hero = CMSSection::where('section_key', 'hero_beranda')->first();

            if (!$hero) {
                return redirect()->back()->with('error', 'Hero section tidak ditemukan!');
            }

            $subtitle = implode('|', [
                $request->subtitle_line1,
                $request->subtitle_line2,
                $request->subtitle_line3,
            ]);

            $updateData = [
                'title' => $request->title,
                'subtitle' => $subtitle,
                'button1_text' => $request->button1_text,
                'button1_link' => $request->button1_link,
                'button2_text' => $request->button2_text,
                'button2_link' => $request->button2_link,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('image')) {
                if ($hero->image && file_exists(public_path($hero->image))) {
                    unlink(public_path($hero->image));
                }

                $image = $request->file('image');
                $imageName = 'hero-image-' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $updateData['image'] = 'images/' . $imageName;
            }

            if ($request->hasFile('logo')) {
                if ($hero->logo && file_exists(public_path($hero->logo))) {
                    unlink(public_path($hero->logo));
                }

                $logo = $request->file('logo');
                $logoName = 'hero-logo-' . time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('images'), $logoName);
                $updateData['logo'] = 'images/' . $logoName;
            }

            $hero->update($updateData);

            return redirect()->route('admin.cms.index')
                ->with('success', '✅ Hero section berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Hero Update Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function aboutUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $about = CMSSection::where('section_key', 'about_company')->first();

            if (!$about) {
                return redirect()->back()->with('error', 'About section tidak ditemukan!');
            }

            $updateData = [
                'title' => $request->title,
                'content' => $request->content,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('background_image')) {
                if ($about->background_image && file_exists(public_path($about->background_image))) {
                    unlink(public_path($about->background_image));
                }

                $bg = $request->file('background_image');
                $bgName = 'about-bg-' . time() . '.' . $bg->getClientOriginalExtension();
                $bg->move(public_path('images'), $bgName);
                $updateData['background_image'] = 'images/' . $bgName;
            }

            $about->update($updateData);

            return redirect()->route('admin.cms.index')
                ->with('success', '✅ About section berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('About Update Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function servicesUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'button1_text' => 'nullable|string|max:100',
            'button1_link' => 'nullable|string|max:255',
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $services = CMSSection::where('section_key', 'services_title')->first();

            if (!$services) {
                return redirect()->back()->with('error', 'Services section tidak ditemukan!');
            }

            $updateData = [
                'title' => $request->title,
                'button1_text' => $request->button1_text,
                'button1_link' => $request->button1_link,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('background_image')) {
                if ($services->background_image && file_exists(public_path($services->background_image))) {
                    unlink(public_path($services->background_image));
                }

                $bg = $request->file('background_image');
                $bgName = 'services-bg-' . time() . '.' . $bg->getClientOriginalExtension();
                $bg->move(public_path('images'), $bgName);
                $updateData['background_image'] = 'images/' . $bgName;
            }

            $services->update($updateData);

            return redirect()->route('admin.cms.index')
                ->with('success', '✅ Services section berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Services Update Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function catalogHeroUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:500',
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $catalog = CMSSection::where('section_key', 'catalog_hero')->first();

            if (!$catalog) {
                return redirect()->back()->with('error', 'Catalog hero section tidak ditemukan!');
            }

            $updateData = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('background_image')) {
                if ($catalog->background_image && file_exists(public_path($catalog->background_image))) {
                    unlink(public_path($catalog->background_image));
                }

                $bg = $request->file('background_image');
                $bgName = 'catalog-bg-' . time() . '.' . $bg->getClientOriginalExtension();
                $bg->move(public_path('images/catalog'), $bgName);
                $updateData['background_image'] = 'images/catalog/' . $bgName;
            }

            $catalog->update($updateData);

            return redirect()->route('admin.cms.index')
                ->with('success', '✅ Catalog hero section berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Catalog Hero Update Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function proyekHeroUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'button1_text' => 'nullable|string|max:100',
            'button1_link' => 'nullable|string|max:500',
            'background_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $proyek = CMSSection::where('section_key', 'proyek_hero')->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek hero section tidak ditemukan!');
            }

            $updateData = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'button1_text' => $request->button1_text,
                'button1_link' => $request->button1_link,
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('background_image')) {
                if ($proyek->background_image && file_exists(public_path($proyek->background_image))) {
                    unlink(public_path($proyek->background_image));
                }

                $bg = $request->file('background_image');
                $bgName = 'proyek-bg-' . time() . '.' . $bg->getClientOriginalExtension();
                $bg->move(public_path('images'), $bgName);
                $updateData['background_image'] = 'images/' . $bgName;
            }

            $proyek->update($updateData);

            return redirect()->route('admin.cms.index')
                ->with('success', '✅ Proyek hero section berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Proyek Hero Update Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========================================
    // CATALOG PRODUCTS MANAGEMENT - START
    // ========================================

    public function catalogProductStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'ecommerce_link' => 'nullable|url|max:500',
            'category' => 'required|string|max:100',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'name.required' => 'Nama produk wajib diisi!',
            'category.required' => 'Kategori produk wajib dipilih!',
            'image.required' => 'Gambar produk wajib diupload!',
            'image.image' => 'File harus berupa gambar!',
            'image.mimes' => 'Format gambar harus: jpeg, jpg, png, atau webp!',
            'image.max' => 'Ukuran gambar maksimal 5MB!',
            'ecommerce_link.url' => 'Format link e-commerce tidak valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $catalog = CMSSection::where('section_key', 'catalog_hero')->first();

            if (!$catalog) {
                return redirect()->back()->with('error', 'Catalog section tidak ditemukan!');
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'product-' . time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                if (!file_exists(public_path('images/catalog/products'))) {
                    mkdir(public_path('images/catalog/products'), 0777, true);
                }
                
                $image->move(public_path('images/catalog/products'), $imageName);
                $imagePath = 'images/catalog/products/' . $imageName;
            }

            $productData = [
                'name' => $request->name,
                'ecommerce_link' => $request->ecommerce_link,
                'category' => $request->category,
                'image' => $imagePath,
            ];

            $catalog->addCatalogProduct($productData);
            $catalog->save();

            if (!in_array($request->category, $catalog->catalog_categories)) {
                $catalog->addCatalogCategory($request->category);
                $catalog->save();
            }

            return redirect()->back()
                ->with('success', '✅ Produk berhasil ditambahkan ke catalog!');

        } catch (\Exception $e) {
            \Log::error('Catalog Product Store Error: ' . $e->getMessage());
            
            if (isset($imagePath) && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function catalogProductDelete($productId)
    {
        try {
            $catalog = CMSSection::where('section_key', 'catalog_hero')->first();

            if (!$catalog) {
                return redirect()->back()->with('error', 'Catalog section tidak ditemukan!');
            }

            $product = $catalog->getCatalogProduct($productId);

            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan!');
            }

            $catalog->deleteCatalogProduct($productId);
            $catalog->save();

            return redirect()->back()
                ->with('success', '✅ Produk berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Catalog Product Delete Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function catalogProductDeleteImage($productId)
    {
        try {
            $catalog = CMSSection::where('section_key', 'catalog_hero')->first();

            if (!$catalog) {
                return redirect()->back()->with('error', 'Catalog section tidak ditemukan!');
            }

            $product = $catalog->getCatalogProduct($productId);

            if (!$product) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan!');
            }

            if (isset($product['image']) && $product['image'] && file_exists(public_path($product['image']))) {
                @unlink(public_path($product['image']));
            }

            $catalog->updateCatalogProduct($productId, ['image' => null]);
            $catalog->save();

            return redirect()->back()
                ->with('success', '✅ Gambar produk berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Catalog Product Delete Image Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function catalogCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
        ], [
            'category.required' => 'Nama kategori wajib diisi!',
            'category.max' => 'Nama kategori maksimal 100 karakter!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $catalog = CMSSection::where('section_key', 'catalog_hero')->first();

            if (!$catalog) {
                return redirect()->back()->with('error', 'Catalog section tidak ditemukan!');
            }

            if (in_array($request->category, $catalog->catalog_categories)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kategori sudah ada!');
            }

            $catalog->addCatalogCategory($request->category);
            $catalog->save();

            return redirect()->back()
                ->with('success', '✅ Kategori berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Catalog Category Store Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function catalogCategoryDelete($category)
    {
        try {
            $catalog = CMSSection::where('section_key', 'catalog_hero')->first();

            if (!$catalog) {
                return redirect()->back()->with('error', 'Catalog section tidak ditemukan!');
            }

            $productsWithCategory = $catalog->getCatalogProductsByCategory($category);
            
            if (count($productsWithCategory) > 0) {
                return redirect()->back()
                    ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh ' . count($productsWithCategory) . ' produk!');
            }

            $catalog->deleteCatalogCategory($category);
            $catalog->save();

            return redirect()->back()
                ->with('success', '✅ Kategori berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Catalog Category Delete Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========================================
    // CATALOG PRODUCTS MANAGEMENT - END
    // ========================================

    // ========================================
    // PROJECT GALLERY MANAGEMENT - START
    // ========================================

        public function projectStore(Request $request)
    {
        // Validasi TANPA kategori dan external_link
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:2000',
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'name.required' => 'Nama proyek wajib diisi!',
            'name.min' => 'Nama proyek minimal 3 karakter!',
            'name.max' => 'Nama proyek maksimal 255 karakter!',
            'description.required' => 'Deskripsi proyek wajib diisi!',
            'description.min' => 'Deskripsi proyek minimal 10 karakter!',
            'description.max' => 'Deskripsi proyek maksimal 2000 karakter!',
            'image.required' => 'Gambar proyek wajib diupload!',
            'image.image' => 'File harus berupa gambar!',
            'image.mimes' => 'Format gambar harus: JPEG, JPG, PNG, atau WEBP!',
            'image.max' => 'Ukuran gambar maksimal 5MB!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $proyek = CMSSection::where('section_key', 'proyek_hero')->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek section tidak ditemukan!');
            }

            // Upload gambar
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'project-' . time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Buat folder jika belum ada
                if (!file_exists(public_path('images/proyek/gallery'))) {
                    mkdir(public_path('images/proyek/gallery'), 0777, true);
                }
                
                $image->move(public_path('images/proyek/gallery'), $imageName);
                $imagePath = 'images/proyek/gallery/' . $imageName;
            }

            // Data proyek TANPA kategori dan external_link
            $projectData = [
                'name' => $request->name,
                'description' => $request->description,
                'image' => $imagePath,
            ];

            // Simpan proyek
            $proyek->addProject($projectData);
            $proyek->save();

            return redirect()->back()
                ->with('success', '✅ Proyek "' . $request->name . '" berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Project Store Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Hapus gambar jika upload berhasil tapi save gagal
            if (isset($imagePath) && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function projectDelete($projectId)
    {
        try {
            $proyek = CMSSection::where('section_key', 'proyek_hero')->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek section tidak ditemukan!');
            }

            $project = $proyek->getProject($projectId);

            if (!$project) {
                return redirect()->back()->with('error', 'Proyek tidak ditemukan!');
            }

            $proyek->deleteProject($projectId);
            $proyek->save();

            return redirect()->back()
                ->with('success', '✅ Proyek berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Project Delete Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function projectDeleteImage($projectId)
    {
        try {
            $proyek = CMSSection::where('section_key', 'proyek_hero')->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek section tidak ditemukan!');
            }

            $project = $proyek->getProject($projectId);

            if (!$project) {
                return redirect()->back()->with('error', 'Proyek tidak ditemukan!');
            }

            if (isset($project['image']) && $project['image'] && file_exists(public_path($project['image']))) {
                @unlink(public_path($project['image']));
            }

            $proyek->updateProject($projectId, ['image' => null]);
            $proyek->save();

            return redirect()->back()
                ->with('success', '✅ Gambar proyek berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Project Delete Image Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function projectCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
        ], [
            'category.required' => 'Nama kategori wajib diisi!',
            'category.max' => 'Nama kategori maksimal 100 karakter!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal! Periksa kembali input Anda.');
        }

        try {
            $proyek = CMSSection::where('section_key', 'proyek_hero')->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek section tidak ditemukan!');
            }

            if (in_array($request->category, $proyek->project_categories)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kategori sudah ada!');
            }

            $proyek->addProjectCategory($request->category);
            $proyek->save();

            return redirect()->back()
                ->with('success', '✅ Kategori berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Project Category Store Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function projectCategoryDelete($category)
    {
        try {
            $proyek = CMSSection::where('section_key', 'proyek_hero')->first();

            if (!$proyek) {
                return redirect()->back()->with('error', 'Proyek section tidak ditemukan!');
            }

            $projectsWithCategory = $proyek->getProjectsByCategory($category);
            
            if (count($projectsWithCategory) > 0) {
                return redirect()->back()
                    ->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh ' . count($projectsWithCategory) . ' proyek!');
            }

            $proyek->deleteProjectCategory($category);
            $proyek->save();

            return redirect()->back()
                ->with('success', '✅ Kategori berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('Project Category Delete Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========================================
    // PROJECT GALLERY MANAGEMENT - END
    // ========================================
}