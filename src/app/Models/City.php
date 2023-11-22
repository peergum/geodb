<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $attributes = [
        'name',
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

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

}
