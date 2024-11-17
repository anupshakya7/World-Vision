<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'country_code',
        'parent_id',
        'latitude',
        'longitude',
        'bounding_box',
        'geometry',
        'level',
    ];


}
