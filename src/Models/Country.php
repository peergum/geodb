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
        'feature_class',
        'feature_code',
        'latitude',
        'longitude',
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
