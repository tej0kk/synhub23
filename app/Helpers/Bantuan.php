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

    public static function generateRandomString($length = 9) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}