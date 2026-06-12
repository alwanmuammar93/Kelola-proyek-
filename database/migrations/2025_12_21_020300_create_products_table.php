<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id_product');
            $table->string('name')->comment('Nama produk');
            $table->string('category', 50)->comment('Kategori produk: alat-las, Perlengkapan, perkakas, Cat');
            $table->string('image')->comment('Path gambar produk');
            $table->string('ecommerce', 20)->default('shopee')->comment('Platform: shopee, tokopedia, tiktok, facebook, whatsapp');
            $table->text('link')->comment('Link ke halaman produk di e-commerce');
            $table->text('description')->nullable()->comment('Deskripsi singkat produk (opsional)');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Status tampil/tidak');
            $table->integer('display_order')->default(0)->comment('Urutan tampilan produk');
            $table->unsignedBigInteger('created_by')->nullable()->comment('User yang membuat');
            $table->timestamps();

            // Foreign key
            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('category');
            $table->index('status');
            $table->index('display_order');
            $table->index(['category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};