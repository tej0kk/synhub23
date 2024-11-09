<?php

namespace App\Http\Controllers\dashboar;

use App\Helpers\Bantuan;
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
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->first();
        if ($pesanan) {
            $status = Bantuan::statusInt($pesanan->status);
            if ($status == 1) {
                $pesanan->update(['status' => 2]);
            } elseif ($status == 2) {
                $pesanan->update(['status' => 3]);
            } elseif ($status == 3) {
                $pesanan->update(['status' => 4]);
            } elseif ($status == 4) {
                $pesanan->update(['status' => 5]);
            } else {
                return response()->json([
                    'message' => 'Maaf, Pesanan Tidak Valid !'
                ], 422);
            }
            return response()->json([
                'message' => 'Status Berhasil Diupdate',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, Pesanan Tidak Valid !'
            ], 422);
        }
    }
}
