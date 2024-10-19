<?php

namespace App\Http\Controllers\dashboar;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::with('fasilitas')->get();
        return $produk;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto'            => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'judul_pendek'    => 'required',
            'judul_panjang'   => 'required',
            'subjudul'        => 'required',
            'harga'           => 'required',
            'satuan'          => 'required',
            'fasilitas'       => 'required|array',
            'fasilitas.*'     => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('foto');
        $image->storeAs('public/produk', $image->hashName());

        //create Slider
        $produk = Produk::create([
            'foto'            => $image->hashName(),
            'judul_pendek'    => $request->judul_pendek,
            'judul_panjang'   => $request->judul_panjang,
            'subjudul'        => $request->subjudul,
            'harga'           => $request->harga,
            'satuan'          => $request->satuan,
        ]);

        foreach($request->fasilitas as $item)
        {
            $fasilitas = Fasilitas::create([
                'produk_id'   => $produk->id,
                'keterangan'  => $item
            ]);
        }

        if ($produk and $fasilitas) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil disimpan';
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        // $produk = Produk::whereId($id)->first();

        if ($produk) {
            return $produk;
        } else {
            return 'Maaf, data tidak ditemukan';
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(), [
            'foto'            => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'judul_pendek'    => 'required',
            'judul_panjang'   => 'required',
            'subjudul'        => 'required',
            'harga'           => 'required',
            'satuan'          => 'required',
            'fasilitas'       => 'required|array',
            'fasilitas.*'     => 'required|string|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('foto')) {
            Storage::disk('local')->delete('public/produk/' . basename($produk->foto));

            $image = $request->file('foto');
            $image->storeAs('public/produk', $image->hashName());

            $produk->update([
                'foto'            => $image->hashName(),
                'judul_pendek'    => $request->judul_pendek,
                'judul_panjang'   => $request->judul_panjang,
                'subjudul'        => $request->subjudul,
                'harga'           => $request->harga,
                'satuan'          => $request->satuan
            ]);
        }

        $produk->update([
            'judul_pendek'    => $request->judul_pendek,
            'judul_panjang'   => $request->judul_panjang,
            'subjudul'        => $request->subjudul,
            'harga'           => $request->harga,
            'satuan'          => $request->satuan
        ]);

        if ($produk) {
            return 'Data Berhasil diupdate';
        } else {
            return 'Maaf, data tidak berhasil diupdate';
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        Storage::disk('local')->delete('public/produk/' . basename($produk->foto));

        if ($produk->delete()) {
            return 'Data Berhasil Disimpan';
        } else {
            return 'Maaf, data belum berhasil dihapus';
        }
    }
}
