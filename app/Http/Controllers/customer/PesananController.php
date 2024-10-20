<?php

namespace App\Http\Controllers\customer;

use App\Helpers\Bantuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->with('produk', 'bayar')
            ->get();

        return $pesanan;
    }

    public function store(Request $request)
    {

        $rules = [
            'user'      => 'required',
            'produk'    => 'required',
            'tanggal_1' => 'required',
            'bayar'     => 'required'
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
                case 'ruang-coworking':
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

        if ($kategori == 'ruangmeeting') {
            $durasi = $request->jam_2 - $request->jam_1;
            $total = $durasi * $produk->harga;
        } else if ($kategori == 'ruangrapat') {
            $durasi = $request->jam_2 - $request->jam_1;
            $total = $durasi * $produk->harga;
        } else if ($kategori == 'ruang-coworking') {
            $durasi = $request->jam_2 - $request->jam_1;
            $total = $durasi * $produk->harga;
        }

        $kode_pesanan = 'SYB' . Bantuan::code($kategori) . time() . Bantuan::generateRandomString();
        // return response()->json([
        //     'durasi' => $durasi,
        //     'total'  => $total,
        //     'kode'   => $kode_pesanan
        // ]);

        $pesanan = Pesanan::create([
            'kode_pesanan'      => $kode_pesanan,
            'user_id'           => Auth::user()->id,
            'produk_id'         => $request->produk,
            'perusahaan'        => $request->perusahaan,
            'jumlah_orang'      => $request->jumlah_orang,
            'tanggal_1'         => $request->tanggal_1,
            'tanggal_2'         => $request->tanggal_2,
            'jam_1'             => $request->jam_1,
            'jam_2'             => $request->jam_2,
            'keterangan'        => $request->keterangan,
            'bayar_id'             => $request->bayar,
            'status'            => 1,
            'durasi'            => $durasi,
            'total'             => $total
        ]);

        if ($pesanan) {
            return 'Pesanan Berhasil';
        } else {
            return 'Maaf, Pesanan Belum Berhasil !';
        }
    }

    public function uploadBukti(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto'      => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'kode_pesanan' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $pesanan = Pesanan::where('kode_pesanan', $request->kode_pesanan)->first();

        if(!$pesanan)
        {
            return 'Pesanan tidak valid';
        }

        $image = $request->file('foto');
        $image->storeAs('public/pesanan', $image->hashName());


        $pesanan->update([
            'bukti'     => $image->hashName(),
            'status'    => 2
        ]);

        return 'Bukti Pembayaran Berhasil Diupload';
    }
}