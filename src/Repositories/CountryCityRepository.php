<?php

namespace Peergum\GeoDB\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Peergum\GeoDB\Interfaces\CountryCityRepositoryInterface;
use Peergum\GeoDB\Models\City;
use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Models\State;

class CountryCityRepository implements CountryCityRepositoryInterface
{
    public function getAllCities()
    {
        return City::all();
    }

    public function getCountryCities($countryId, array $fields = ['*'])
    {
        return City::where('country_id', '=', $countryId)
            ->orderBy('ascii_name', 'asc')
            ->get($fields);
    }

//    public function getStateCities($stateId)
//    {
//        return City::where('state_id','=',$stateId)->get();
//    }

    public function getCitiesLike($cityName, $country = 0, array $fields = ['*'])
    {
        // ensure only letters and _ for safer and more efficient search
        $filter = preg_replace('/[^a-z]/', '_', $cityName);
//        return DB::table('cities')
//            ->leftJoin('countries', 'countries.id', '=', 'cities.country_id')
//            ->join('states', function (JoinClause $join) {
//                $join->on('states.country_id', '=', 'countries.id')
//                    ->on('states.admin1', '=', 'cities.admin1')
//                    ->on(function (JoinClause $join) {
//                        $join->on('states.admin2', '=', 'cities.admin2')
//                            ->orWhere('states.feature_code', '=', 'ADM1');
//                    });
//            })
//            ->when($country, function (Builder $query, $country) {
//                $query->where('countries.id', '=', $country);
//            })
//            ->where('cities.population', '>', 0)
//            ->where('cities.ascii_name', 'like', "{$cityName}%", 'and')
//            ->orderBy('cities.name', 'asc')
//            ->limit(50)
//            ->get();

        header('content-type: text/plain');
        return City::with('country')
//            ->join('states', function (JoinClause $join) {
//                $join->on('states.country_id', '=', 'countries.id')
//                    ->on('states.admin1', '=', 'cities.admin1')
//                    ->on(function (JoinClause $join) {
//                        $join->on('states.admin2', '=', 'cities.admin2')
//                            ->orWhere('states.feature_code', '=', 'ADM1');
//                    });
//            })
            ->when($country, function (Builder $query, $country) {
                $query->where('country_id', '=', $country);
            })
            ->where('cities.population', '>', 0)
            ->where('cities.ascii_name', 'like', "{$cityName}%", 'and')
            ->orderBy('cities.name', 'asc')
            ->limit(50)
            ->get();
//            return City::where('ascii_name', 'like', "{$cityName}%")
//                ->with('country')
//                ->where('country_id','=',$country)
//                ->where('population','>',0)
//                ->orderBy('name', 'asc')
//                ->limit(10)
//                ->get($fields);
//        } else {
//            return City::where('ascii_name', 'like', "{$cityName}%")
//                ->with('country')
//                ->where('population', '>', 0)
//                ->orderBy('name', 'asc')
//                ->limit(10)
//                ->get($fields);
//        }
    }

    public function createCity(array $cityDetails)
    {
        City::create($cityDetails);
    }

    public function updateCity($cityId, array $newDetails)
    {
        City::findOrFail($cityId)->first()->update($newDetails);
    }
}
