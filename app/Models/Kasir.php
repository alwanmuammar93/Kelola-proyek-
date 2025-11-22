<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kasir';
    protected $fillable = ['id_user', 'durasi_aktif', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
