<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'country_id',
        'country_phone_code',
        'country_name',
        'country_bn_name',
        'country_short_name',
        'country_population',
    ];
}
