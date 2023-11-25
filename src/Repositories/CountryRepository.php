<?php

namespace Peergum\GeoDB\Repositories;

use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Interfaces\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{
    public function getAllCountries() {
        return Country::all();
    }

    public function getCountryById($countryId) {
        return Country::where('cc','=',$countryId)
            ->orWhere('cc2','=',$countryId);
    }

    public function getCountriesLike($countryName) {
        return Country::where('name','like', $countryName);
    }

    public function createCountry(array $countryDetails)
    {
        Country::create($countryDetails);
    }

    public function updateCountry($countryId, array $newDetails)
    {
        Country::findOrFail($countryId)->first()->update($newDetails);
    }
}
