<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Danau extends Model
{
    use HasFactory;

    protected $table = 'danau';
    protected $guarded = ['id'];
}
