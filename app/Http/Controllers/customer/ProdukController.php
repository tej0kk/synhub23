<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        $produk = Produk::with('fasilitas')->get();

        return $produk;
    }

    public function show($slug)
    {
        $produk = Produk::where('slug', $slug)
            ->with('fasilitas')->first();

        if ($produk) {
            return $produk;
        } else {
            return 'Maaf, Data tidak ditemukan';
        }
    }
}
