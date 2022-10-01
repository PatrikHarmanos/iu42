<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mayor_name',
        'img_path',
        'number',
        'fax',
        'address',
        'web',
        'email',
        'latitude',
        'longitude'
    ];
}
