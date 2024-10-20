<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function bayar()
    {
        return $this->belongsTo(Bayar::class);
    }

    public function getBuktiAttribute($bukti)
    {
        return url('storage/pesanan/' . $bukti);
    }

    public function getStatusAttribute($status)
    {
        if ($status == "1") {
            return 'Silahkan Lakukan Pembayaran';
        } else if ($status == "2") {
            return 'Menunggu Konfirmasi Pembayaran';
        } else if ($status == "3") {
            return 'Pembayaran Telah Dikonfirmasi';
        } else if ($status == "4") {
            return 'Pesanan Selesai';
        }
    }
}
