<?php

namespace App\Http\Controllers\dashboar;

use App\Http\Controllers\Controller;
use App\Models\Bayar;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BayarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bayar = Bayar::paginate(10);
        return $bayar;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo'             => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'nama_orang'       => 'required',
            'nama_pembayaran'  => 'required',
            'nomor_rekening'   => 'required',
            'status'           => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('logo');
        $image->storeAs('public/bayar', $image->hashName());

        //create Slider
        $bayar = Bayar::create([
            'logo'             => $image->hashName(),
            'nama_orang'       => $request->nama_orang,
            'nama_pembayaran'  => $request->nama_pembayaran,
            'nomor_rekening'   => $request->nomor_rekening,
            'status'           => $request->status
        ]);

        if ($bayar) {
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
    public function show(Bayar $bayar)
    {
        if ($bayar) {
            return $bayar;
        } else {
            return response()->json([
                'message' => 'Maaf, data tidak valid !'
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bayar $bayar)
    {
        $validator = Validator::make($request->all(), [
            'logo'             => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'nama_orang'       => 'required',
            'nama_pembayaran'  => 'required',
            'nomor_rekening'   => 'required',
            'status'           => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->file('logo')) {
            Storage::disk('local')->delete('public/bayar/' . basename($bayar->logo));
            $image = $request->file('logo');
            $image->storeAs('public/bayar', $image->hashName());

            $bayar->update([
                'logo'             => $image->hashName(),
                'nama_orang'       => $request->nama_orang,
                'nama_pembayaran'  => $request->nama_pembayaran,
                'nomor_rekening'   => $request->nomor_rekening,
                'status'           => $request->status
            ]);
        }
        $bayar->update([
            'nama_orang'       => $request->nama_orang,
            'nama_pembayaran'  => $request->nama_pembayaran,
            'nomor_rekening'   => $request->nomor_rekening,
            'status'           => $request->status
        ]);

        if ($bayar) {
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
    public function destroy(Bayar $bayar)
    {
        Storage::disk('local')->delete('public/bayar/' . basename($bayar->logo));

        if ($bayar->delete()) {
            return response()->json([
                'message' => 'Data Berhasil dihapus',
            ], 202);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil dihapus'
            ], 422);
        }
    }

    public function ubahStatus(Bayar $bayar)
    {
        $bayar->update(['status', ($bayar->status) == 'n' ? 'y' : 'n']);
        return 'Status Pembayaran Berhasil diubah';
    }
}
