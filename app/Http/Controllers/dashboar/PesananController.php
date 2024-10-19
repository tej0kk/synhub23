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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules = [
            'user'      => 'required',
            'produk'    => 'required',
            'tanggal_1' => 'required',
        ];

        $messages = [
            'user.required'  =>  'Silahkan Login Terlebih Dahulu !!',
            'tanggal.required'  =>  'Silahkan Masukkan Tanggal Pemesanan !!',
        ];

        $produk = Produk::whereId($request->produk)->first();

        if ($produk) {
            $tamp1 = strtolower($produk->judul_pendek);
            $kategori = preg_replace('/\s+/', '', $tamp1);

            switch ($kategori) {
                case 'ruangmeeting':
                    $rules['perusahaan'] = 'required';
                    $rules['jumlah_orang'] = 'required';
                    $rules['jam_1'] = 'required';
                    $rules['jam_2'] = 'required';
                    $rules['keterangan'] = 'required';
                    break;
                case 'ruangacara':
                    $rules['perusahaan'] = 'required';
                    $rules['jumlah_orang'] = 'required';
                    $rules['tanggal_2'] = 'required';
                    $rules['keterangan'] = 'required';
                    break;
                case 'coworkingspace':
                    $rules['jam_1'] = 'required';
                    $rules['jam_2'] = 'required';
                    break;
            }
        } else {
            return response()->json('Error !', 422);
        }


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $kode_pesanan = 'SYB' . Bantuan::code($kategori) . time() . Bantuan::generateRandomString();
        $pesanan = Pesanan::create([
            'kode_pesanan'      => $kode_pesanan,
            'user_id'           => Auth()->user()->id,
            'produk_id'         => $request->produk,
            'perusahaan'        => $request->perusahaan,
            'jumlah_orang'      => $request->jumlah_orang,
            'tanggal_1'         => $request->tanggal_1,
            'tanggal_2'         => $request->tanggal_2,
            'jam_1'             => $request->jam_1,
            'jam_2'             => $request->jam_2,
            'keterangan'        => $request->keterangan,
            'status'            => 1,
        ]);

        if ($pesanan) {
            return 'Pesanan Berhasil';
        } else {
            return 'Maaf, Pesanan Belum Berhasil !';
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        if ($pesanan) {
            return $pesanan;
        } else {
            return 'Maaf, data tidak ditemukan';
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pesanan $pesanan)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pesanan $pesanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pesanan $pesanan)
    {
        //
    }
}
