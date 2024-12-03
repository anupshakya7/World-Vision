<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'variablename_long',
        'variablename',
        'vardescription',
        'varunits',
        'is_more_better',
        'transformation',
        'lower',
        'upper',
        'sourcelinks',
        'subnational',
        'national',
        'imputation',
        'created_by',
        'company_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function source()
    {
        return $this->hasMany(Source::class, 'indicator_id');
    }
}
