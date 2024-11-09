<?php

namespace App\Http\Controllers\dashboar;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::paginate(10);
        return $pesanan;
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        if ($pesanan) {
            return $pesanan;
        } else {
            return response()->json([
                'message' => 'Maaf, Pesanan Tidak Valid !'
            ], 422);
        }
    }

    public function ubahStatus($kode_pesanan)
    {
        $status = Pesanan::where('kode_pesanan', $kode_pesanan)->first();

        if ($status) {
            return $status->getOriginal('status'); // Mendapatkan nilai asli angka
        } else {
            return 'Pesanan tidak ditemukan';
        }
    }
}
