<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryData extends Model
{
    use HasFactory;

    protected $fillable=[
        'indicator_id',
        'countrycode',
        'year',
        'country_score',
        'country_col',
        'country_cat',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class, 'indicator_id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'countrycode','country_code');
    }
}
