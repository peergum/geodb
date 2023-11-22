<?php

namespace App\Repositories;

use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country;

class CountryRepository implements CountryRepositoryInterface
{
    public function getAllCountries() {
        return Country::all();
    }

    public function getCountryById($countryId) {
        return Country::where('cc','=',$countryId)
            ->orWhere('cc2','=',$countryId);
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
