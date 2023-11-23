<?php

namespace Peergum\GeoDB\Interfaces;

interface CountryRepositoryInterface
{
    public function getAllCountries();
    public function getCountryById($countryId);
    public function getCountriesLike($countryName);

//    public function deleteCountry($countryId);
    public function createCountry(array $countryDetails);
    public function updateCountry($countryId, array $newDetails);
}
