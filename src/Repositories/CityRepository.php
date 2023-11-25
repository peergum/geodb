<?php

namespace Peergum\GeoDB\Repositories;

use Peergum\GeoDB\Interfaces\CityRepositoryInterface;
use Peergum\GeoDB\Models\City;

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

    public function updateCity($cityId, array $newDetails)
    {
        City::findOrFail($cityId)->first()->update($newDetails);
    }
}
