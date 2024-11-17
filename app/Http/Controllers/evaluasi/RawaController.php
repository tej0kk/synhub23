<?php

namespace App\Http\Controllers\evaluasi;

use App\Http\Controllers\Controller;
use App\Models\Rawa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RawaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rawa = Rawa::all();
        return $rawa;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_rawa'        => 'required',
            'luas'           => 'required',
            'kedalaman'             => 'required',
            'kriteria'  => 'required|in:payau,lebak,tawar',
        ], [
            'nama_rawa.required' => 'Silahkan masukkan nama jalan !',
            'luas.required' => 'Silahkan masukkan luas jalan !',
            'kedalaman.required' => 'Silahkan masukkan kedalaman jalan !',
            'kriteria.required' => 'Maaf, jenis perkerasan tidak valid !',
            'kriteria.in' => 'Maaf, jenis perkerasan tidak valid !',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $rawa = Rawa::create([
            'nama_rawa'      => $request->nama_rawa,
            'luas'   => $request->luas,
            'kedalaman'     => $request->kedalaman,
            'kriteria'     => $request->kriteria
        ]);

        if ($rawa) {
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
