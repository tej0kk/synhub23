<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $guarded = ['id'];

    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function getFotoAttribute($foto)
    {
        return url('storage/produk/' . $foto);
    }

}
