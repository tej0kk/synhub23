<?php

namespace App\Http\Controllers\evaluasi;

use App\Http\Controllers\Controller;
use App\Models\Danau;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DanauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $danau = Danau::all();
        return $danau;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_danau'        => 'required',
            'alamat'           => 'required',
            'volume'             => 'required',
            'kondisi'  => 'required|in:terawat,tidak,beton',
        ], [
            'nama_danau.required' => 'Silahkan masukkan nama danau !',
            'alamat.required' => 'Silahkan masukkan alaman danau !',
            'volume.required' => 'Silahkan masukkan volume jalan !',
            'kondisi.required' => 'Maaf, kondisi tidak valid !',
            'kondisi.in' => 'Maaf, kondisi tidak valid !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $danau = Danau::create([
            'nama_danau'      => $request->nama_danau,
            'alamat'   => $request->alamat,
            'volume'     => $request->volume,
            'kondisi'     => $request->kondisi
        ]);

        if ($danau) {
            return response()->json([
                'message' => 'Data Berhasil Disimpan',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Maaf, data belum berhasil disimpan'
            ], 400);
        }
    }
}
