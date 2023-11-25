<?php

namespace Peergum\GeoDB\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $attributes = [
        'name',
        'cc',
        'cc2',
        'sqkm',
        'population',
        'continent',
        'tld',
        'currency_code',
        'currency_name',
        'lang',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

}
