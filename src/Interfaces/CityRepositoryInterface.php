<?php

namespace Peergum\GeoDB\Interfaces;

interface CityRepositoryInterface
{
    public function getAllCities();

    public function getCountryCities($countryId);
    public function getStateCities($stateId);
    public function getCitiesLike($cityName);

//    public function deleteCountry($countryId);
    public function createCity(array $cityDetails);
    public function updateCity($cityId, array $newDetails);
}
