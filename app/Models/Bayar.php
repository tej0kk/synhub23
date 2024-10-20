<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayar extends Model
{
    use HasFactory;

    protected $table = 'bayar';

    protected $guarded = ['id'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    public function getLogoAttribute($logo)
    {
        return url('storage/bayar/' . $logo);
    }
}
