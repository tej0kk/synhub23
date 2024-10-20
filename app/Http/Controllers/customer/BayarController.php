<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Bayar;
use Illuminate\Http\Request;

class BayarController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $bayar = Bayar::where('status', 'y')->get();
        return $bayar;
    }
}
