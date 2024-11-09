<?php

namespace App\Http\Controllers\dashboar;

use App\Helpers\Bantuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $status = Pesanan::where('kode_pesanan', $kode_pesanan)->first()->status;

        return $status;
    }

}
