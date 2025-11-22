<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('id_admin');               // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key ke users.id_user
            $table->boolean('status')->default(true); // Status admin aktif/tidak
            
            $table->foreign('user_id')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');           // Jika user dihapus, admin ikut hilang
                  
            $table->timestamps();                 // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
