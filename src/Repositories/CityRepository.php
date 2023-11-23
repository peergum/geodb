<?php

namespace Peergum\GeoDB\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\StateRepositoryInterface;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class CityRepository implements CityRepositoryInterface
{
    public function getAllCities() {
        return City::all();
    }

    public function getCountryCities($countryId)
    {
        return City::where('country_id','=',$countryId);
    }

    public function getStateCities($stateId)
    {
        return City::where('state_id','=',$stateId);
    }

    public function getCitiesLike($cityName) {
        return City::where('name','like', $cityName);
    }

    public function createCity(array $cityDetails)
    {
        City::create($cityDetails);
    }

    public function updateState($cityId, array $newDetails)
    {
        City::findOrFail($cityId)->first()->update($newDetails);
    }
}
