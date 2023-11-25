<?php

namespace Peergum\GeoDB\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Number;

class City extends Model
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

    protected $appends = [
        'states',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function states(): Attribute
    {
        return Attribute::get(function () {
            return State::where('country_id', '=', $this->country_id)
                ->where('admin1', '=', $this->admin1)
                ->where(function($query) {
                    return $query->where('feature_code', 'like', 'ADM1%')
                        ->orWhere('admin2', '=', $this->admin2);
                })
                ->get();
        });
    }
//    public function stateIds(): Attribute
//    {
//        return Attribute::get(function () {
//            return State::where('country_id', '=', $this->country_id)
//                ->where(function ($query) {
//                    return $query->where(function ($query) {
//                        return $query->where('admin1', '=', $this->admin1)
//                            ->where('feature_code', 'like', 'ADM1%');
//                    })
//                        ->orWhere(function ($query) {
//                            return $query->where('admin1', '=', $this->admin1)
//                                ->where('admin2', '=', $this->admin2);
//                        });
//                })
//                ->get('id')->values();
//        });
//    }

}
