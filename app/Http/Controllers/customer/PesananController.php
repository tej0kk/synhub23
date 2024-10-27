<?php

namespace App\Http\Controllers\customer;

use App\Helpers\Bantuan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Pesanan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::user()->id)
            ->with('produk', 'bayar', 'user')
            ->get();

        return $pesanan;
    }

    public function store(Request $request)
    {

        $rules = [
            // 'user'      => 'required',
            'produk'    => 'required',
            'tanggal_1' => 'required',
            'bayar'     => 'required'
        ];

        $messages = [
            'tanggal_1.required'  =>  'Silahkan Masukkan Tanggal Mulai Acara !!',
            'produk.required'   =>  'Maaf, Ruangan Tidak Valid !!',
            'bayar.required'    =>  'Maaf, Metode Pembayaran Tidak Valid !!'
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
                    $messages['perusahaan.required'] = 'Silahkan masukkan Perusahaan/Instansi Asal Anda !';
                    $messages['jumlah_orang.required'] = 'Silahkan masukkan Jumlah Orang yang akan hadir !';
                    $messages['jam_1.required'] = 'Silahkan masukkan Jam Mulai !';
                    $messages['jam_2.required'] = 'Silahkan masukkan Jam Selesai !';
                    $messages['keterangan.required'] = 'Silahkan masukkan Catatan/Kebutuhan tambahan Anda !';

                    break;
                case 'ruangacara':
                    $rules['perusahaan'] = 'required';
                    $rules['jumlah_orang'] = 'required';
                    $rules['tanggal_2'] = 'required';
                    $rules['keterangan'] = 'required';
                    $messages['perusahaan.required'] = 'Silahkan masukkan Perusahaan/Instansi Asal Anda !';
                    $messages['tanggal_2.required'] = 'Silahkan Masukkan Tanggal Selesai Acara !!';
                    $messages['keterangan.required'] = 'Silahkan masukkan Catatan/Kebutuhan tambahan Anda !';
                    break;
                case 'ruangcoworking':
                    $rules['jam_1'] = 'required';
                    $rules['jam_2'] = 'required';
                    $messages['jam_1.required'] = 'Silahkan masukkan Jam Mulai !';
                    $messages['jam_2.required'] = 'Silahkan masukkan Jam Selesai !';
                    break;
            }
        } else {
            return response()->json([
                'message' => 'Ruangan Tidak tersedia'
            ], 422);
        }


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($kategori == 'ruangmeeting') {
            $durasi = $request->jam_2 - $request->jam_1;
            $total = $durasi * $produk->harga;
        } else if ($kategori == 'ruangacara') {
            $durasi = Carbon::parse($request->tanggal_2)->diffInDays(Carbon::parse($request->tanggal_1));
            $total = $durasi * $produk->harga;
        } else if ($kategori == 'ruangcoworking') {
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
            'bayar_id'          => $request->bayar,
            'status'            => 1,
            'durasi'            => $durasi,
            'total'             => $total
        ]);

        if ($pesanan) {
            return response()->json([
                'message' => 'Terima Kasih, Reservasi Berhasil, Silahkan Lakukan Pembayaran dan upload bukti pembayaran',
                'data'    =>  $kode_pesanan
            ], 201);
        } else {
            return response()->json([
                'message' => 'Maaf, Reservasi Belum Berhasil !'
            ], 422);
        }
    }

    public function uploadBukti(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'kode_pesanan' => 'required'
        ],[
            'foto.required' => 'Silahkan masukkan file foto bukti pembayaran !',
            'foto.image' => 'Maaf file foto bukti pembayaran tidak valid !',
            'foto.mimes' => 'Maaf file foto bukti pembayaran tidak valid !',
            'foto.max' => 'Maaf file foto bukti pembayaran tidak valid, maksimal 2Mb !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $pesanan = Pesanan::where('kode_pesanan', $request->kode_pesanan)->first();

        // return $pesanan;

        if (!$pesanan) {
            return response()->json([
                'message' => 'Maaf, Pesanan Tidak Valid !'
            ], 422);
        }

        $image = $request->file('foto');
        $image->storeAs('public/pesanan', $image->hashName());


        $pesanan->update([
            'bukti'     => $image->hashName(),
            'status'    => 2
        ]);

        return response()->json([
            'message' => 'Bukti Pembayaran Berhasil Diupload'
        ], 202);
    }

    public function show($kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)
            ->where('user_id', Auth::user()->id)
            ->with('produk', 'bayar', 'user')
            ->first();

        if (!$pesanan) {
            return response()->json([
                'message' => 'Maaf, Pesanan Tidak Valid !'
            ], 422);
        }

        return $pesanan;
    }
}
