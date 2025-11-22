<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_user';
    protected $fillable = ['username', 'password', 'email', 'role', 'verified'];

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    public function kasir()
    {
        return $this->hasOne(Kasir::class, 'id_user');
    }
}
