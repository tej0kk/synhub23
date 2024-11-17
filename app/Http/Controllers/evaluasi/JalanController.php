<?php

namespace App\Http\Controllers\evaluasi;

use App\Http\Controllers\Controller;
use App\Models\Jalan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class JalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jalan = Jalan::all();
        return $jalan;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jalan'        => 'required',
            'panjang'           => 'required',
            'lebar'             => 'required',
            'jenis_perkerasan'  => 'required|in:aspal,tanah,beton',
        ], [
            'nama_jalan.required' => 'Silahkan masukkan nama jalan !',
            'panjang.required' => 'Silahkan masukkan panjang jalan !',
            'lebar.required' => 'Silahkan masukkan lebar jalan !',
            'jenis_perkerasan.required' => 'Maaf, jenis perkerasan tidak valid !',
            'jenis_perkerasan.in' => 'Maaf, jenis perkerasan tidak valid !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $jalan = Jalan::create([
            'nama_jalan'      => $request->nama_jalan,
            'panjang'   => $request->panjang,
            'lebar'     => $request->lebar,
            'jenis_perkerasan'     => $request->jenis_perkerasan
        ]);

        if ($jalan) {
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
