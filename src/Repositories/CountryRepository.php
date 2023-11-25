<?php

namespace Peergum\GeoDB\Repositories;

use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Interfaces\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{
    public function getAllCountries(array $fields = ['*'])
    {
        return Country::orderBy('name','asc')->get($fields);
    }

    public function getCountryById($countryId, array $fields = ['*'])
    {
        return Country::where('id', '=', $countryId)
            ->orWhere('cc', '=', strtoupper($countryId))
            ->orWhere('cc2', '=', strtoupper($countryId))
            ->orderBy('name','asc')
            ->get($fields)
            ->first();
    }

    public function getCountriesLike($countryName)
    {
        return Country::where('name', 'like', "{$countryName}%")->orderBy('name','asc')->get();
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
