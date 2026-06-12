<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cms_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key', 50)->unique()->comment('Unique identifier untuk section');
            $table->string('title')->nullable()->comment('Judul section');
            $table->text('subtitle')->nullable()->comment('Subjudul atau tagline (line 1)');
            $table->text('subtitle2')->nullable()->comment('Subjudul line 2 (untuk catalog/proyek)'); // ✅ BARU
            $table->text('content')->nullable()->comment('Konten deskripsi');
            $table->string('image')->nullable()->comment('Path gambar utama');
            $table->string('logo')->nullable()->comment('Path logo');
            $table->string('background_image')->nullable()->comment('Path background image untuk header'); // ✅ BARU
            $table->string('button1_text', 100)->nullable()->comment('Text tombol 1');
            $table->string('button1_link')->nullable()->comment('Link tombol 1');
            $table->string('button2_text', 100)->nullable()->comment('Text tombol 2');
            $table->string('button2_link')->nullable()->comment('Link tombol 2');
            $table->jsonb('metadata')->nullable()->comment('Data tambahan dalam JSON');
            $table->boolean('is_active')->default(true)->comment('Status aktif/tidak');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('User yang terakhir update');
            $table->timestamps();

            // Foreign key
            $table->foreign('updated_by')->references('id_user')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('section_key');
            $table->index('is_active');
        });

        // Insert default data untuk semua sections
        DB::table('cms_sections')->insert([
            // ========================================
            // HERO BERANDA
            // ========================================
            [
                'section_key' => 'hero_beranda',
                'title' => 'PT SURABAYA LAS',
                'subtitle' => 'Solusi Konstruksi & Penjualan|Alat dan Bahan Bangunan|Terbaik Untuk Anda',
                'subtitle2' => null,
                'content' => null,
                'image' => 'images/WhatsApp Image 2025-11-23 at 00.20.09_da1f500c.jpg',
                'logo' => 'images/SURABAYA_LAS_INTI-removebg-preview.png',
                'background_image' => null,
                'button1_text' => 'LIHAT PROYEK KAMI',
                'button1_link' => '/galeri-proyek',
                'button2_text' => 'HUBUNGI KAMI SEKARANG',
                'button2_link' => '/kontak',
                'metadata' => json_encode(['highlight_line' => 3]),
                'is_active' => true,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // ABOUT COMPANY
            // ========================================
            [
                'section_key' => 'about_company',
                'title' => 'PT SURABAYA LAS',
                'subtitle' => null,
                'subtitle2' => null,
                'content' => 'Kami merupakan spesialis jasa bengkel las profesional yang telah berpengalaman puluhan tahun dibidangnya. Komitmen kami adalah memberikan layanan terbaik dalam pekerjaan dan konstruksi pengelasan dengan harga yang kompetitif.',
                'image' => null,
                'logo' => null,
                'background_image' => null,
                'button1_text' => null,
                'button1_link' => null,
                'button2_text' => null,
                'button2_link' => null,
                'metadata' => null,
                'is_active' => true,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // SERVICES TITLE
            // ========================================
            [
                'section_key' => 'services_title',
                'title' => 'Layanan Unggulan Kami',
                'subtitle' => null,
                'subtitle2' => null,
                'content' => null,
                'image' => null,
                'logo' => null,
                'background_image' => null,
                'button1_text' => 'Pelajari Lebih Lanjut',
                'button1_link' => '/tentang-kami',
                'button2_text' => null,
                'button2_link' => null,
                'metadata' => null,
                'is_active' => true,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // ✅ CATALOG HERO (BARU)
            // ========================================
            [
                'section_key' => 'catalog_hero',
                'title' => 'KATALOG PRODUK',
                'subtitle' => 'Jelajahi berbagai produk berkualitas dari PT Surabaya Las',
                'subtitle2' => null,
                'content' => null,
                'image' => null,
                'logo' => null,
                'background_image' => 'images/PRESENTATION (1).png',
                'button1_text' => null,
                'button1_link' => null,
                'button2_text' => null,
                'button2_link' => null,
                'metadata' => null,
                'is_active' => true,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ========================================
            // ✅ PROYEK HERO (BARU)
            // ========================================
            [
                'section_key' => 'proyek_hero',
                'title' => 'LAYANAN KONSTRUKSI',
                'subtitle' => 'PT SURABAYA LAS',
                'subtitle2' => null,
                'content' => null,
                'image' => null,
                'logo' => null,
                'background_image' => 'images/proyek/asasasaasa.jpg',
                'button1_text' => 'HUBUNGI KAMI SEKARANG',
                'button1_link' => 'https://wa.me/6285211887779',
                'button2_text' => null,
                'button2_link' => null,
                'metadata' => null,
                'is_active' => true,
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_sections');
    }
};