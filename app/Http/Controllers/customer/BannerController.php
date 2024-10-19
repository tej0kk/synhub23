<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $banner = Banner::where('status', 'y')
            ->select('judul', 'subjudul', 'foto', 'posisi')
            ->get();
        return $banner;
    }
}
