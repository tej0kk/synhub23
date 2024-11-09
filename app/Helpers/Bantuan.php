<?php

namespace App\Helpers;

class Bantuan
{
    public static function code($val)
    {   
        $hasil = '';
        if($val == 'ruangmeeting')
        {
            $hasil = '-RM-';
        }else if($val == 'ruangacara')
        {
            $hasil = '-RA-';
        }else if($val == 'ruangcoworking')
        {
            $hasil = '-CO-';
        }

        return $hasil;
    }

    public static function generateRandomString($length = 10) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function statusInt($string)
    {
        if ($string == "Silahkan Lakukan Pembayaran") {
            return 1;
        } else if ($string == "Menunggu Konfirmasi Pembayaran") {
            return 2;
        } else if ($string == "Pembayaran Telah Dikonfirmasi") {
            return 3;
        } else if ($string == "Pesanan Selesai") {
            return 4;
        }

        return 0;
    }
}