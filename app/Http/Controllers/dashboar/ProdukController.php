<?php

namespace App\Http\Controllers\dashboar;

use Illuminate\Support\Str;
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
        $validator = Validator::make(
            $request->all(),
            [
                'foto'            => 'required|image|mimes:jpeg,jpg,png|max:2000',
                'judul_pendek'    => 'required',
                'judul_panjang'   => 'required',
                'subjudul'        => 'required',
                'deskripsi'       => 'required',
                'harga'           => 'required|numeric',
                'satuan'          => 'required',
                'fasilitas'       => 'required|array',
                'fasilitas.*'     => 'required|string|min:3',
            ],
            [
                'foto.required' => 'Silahkan Masukkan Foto Produk !',
                'foto.mimes' => 'Maaf file foto tidak valid !',
                'foto.max' => 'Maaf file foto tidak valid, maksimal 2MB  !',
                'judul_pendek.required' => 'Silahkan masukkan judul produk !',
                'judul_panjang.required' => 'Silahkan masukkan judul lengkap produk !',
                'subjudul.required' => 'Silahkan masukkan subjudul produk !',
                'deskripsi.required' => 'Silahkan masukkan deskripsi produk !',
                'satuan.required' => 'Silahkan masukkan satuan penyewaan !',
                'harga.required' => 'Maaf, harga tidak valid !',
                'harga.numeric' => 'Maaf, harga tidak valid !',
                'fasilitas.required' => 'Silahkan masukkan fasilitas !',
                'fasilitas.*' => 'Silahkan masukkan fasilitas !',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('foto');
        $image->storeAs('public/produk', $image->hashName());

        //create Slider
        $produk = Produk::create([
            'foto'            => $image->hashName(),
            'slug'            => Str::slug($request->judul_pendek, '-'),
            'judul_pendek'    => $request->judul_pendek,
            'judul_panjang'   => $request->judul_panjang,
            'subjudul'        => $request->subjudul,
            'deskripsi'       => $request->deskripsi,
            'harga'           => $request->harga,
            'satuan'          => $request->satuan,
        ]);

        foreach ($request->fasilitas as $item) {
            $fasilitas = Fasilitas::create([
                'produk_id'   => $produk->id,
                'keterangan'  => $item
            ]);
        }

        if ($produk and $fasilitas) {
            return response()->json([
                'message' => 'Data Berhasil Disimpan',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil disimpan'
            ], 422);
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
            return response()->json([
                'message' => 'Maaf, data tidak valid !!'
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'foto'            => 'image|mimes:jpeg,jpg,png|max:2000',
                'judul_pendek'    => 'required',
                'judul_panjang'   => 'required',
                'subjudul'        => 'required',
                'deskripsi'       => 'required',
                'harga'           => 'required|numeric',
                'satuan'          => 'required',
                'fasilitas'       => 'required|array',
                'fasilitas.*'     => 'required|string|min:3',
            ],
            [
                'foto.mimes' => 'Maaf file foto tidak valid !',
                'foto.max' => 'Maaf file foto tidak valid, maksimal 2MB  !',
                'judul_pendek.required' => 'Silahkan masukkan judul produk !',
                'judul_panjang.required' => 'Silahkan masukkan judul lengkap produk !',
                'subjudul.required' => 'Silahkan masukkan subjudul produk !',
                'deskripsi.required' => 'Silahkan masukkan deskripsi produk !',
                'satuan.required' => 'Silahkan masukkan satuan penyewaan !',
                'harga.required' => 'Maaf, harga tidak valid !',
                'harga.numeric' => 'Maaf, harga tidak valid !',
                'fasilitas.required' => 'Silahkan masukkan fasilitas !',
                'fasilitas.*' => 'Silahkan masukkan fasilitas !',
            ]
        );

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
                'deskripsi'       => $request->deskripsi,
                'harga'           => $request->harga,
                'satuan'          => $request->satuan
            ]);
            Fasilitas::where('produk_id', $produk->id)->delete();
            foreach ($request->fasilitas as $item) {
                $fasilitas = Fasilitas::create([
                    'produk_id'   => $produk->id,
                    'keterangan'  => $item
                ]);
            }
        }

        $produk->update([
            'judul_pendek'    => $request->judul_pendek,
            'judul_panjang'   => $request->judul_panjang,
            'subjudul'        => $request->subjudul,
            'deskripsi'       => $request->deskripsi,
            'harga'           => $request->harga,
            'satuan'          => $request->satuan
        ]);
        
        Fasilitas::where('produk_id', $produk->id)->delete();
        foreach ($request->fasilitas as $item) {
            $fasilitas = Fasilitas::create([
                'produk_id'   => $produk->id,
                'keterangan'  => $item
            ]);
        }
        if ($produk and $fasilitas) {
            return response()->json([
                'message' => 'Data Berhasil diupdate',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil diupdate'
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        Storage::disk('local')->delete('public/produk/' . basename($produk->foto));

        if ($produk->delete()) {
            return response()->json([
                'message' => 'Data Berhasil dihapus',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil dihapus'
            ], 422);
        }
    }
}
