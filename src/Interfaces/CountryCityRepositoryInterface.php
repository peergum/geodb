<?php

namespace Peergum\GeoDB\Interfaces;

interface CountryCityRepositoryInterface
{
    public function getAllCities();

    public function getCountryCities($countryId, array $fields = ['*']);

//    public function getCountryStateCities($countryId, $stateId, array $fields = ['*']);

    public function getCitiesLike($cityName, $country = 0, array $fields = ['*']);

//    public function deleteCountry($countryId);
    public function createCity(array $cityDetails);

    public function updateCity($cityId, array $newDetails);
}
