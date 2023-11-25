<?php

namespace Peergum\GeoDB\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $attributes = [
        'id',
        'name',
        'ascii_name',
        'latitude',
        'longitude',
        'feature_class',
        'feature_code',
        'admin1',
        'admin2',
        'admin3',
        'admin4',
        'population',
        'elevation',
        'timezone',
        'updated_at',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
