<?php

namespace Peergum\GeoDB\Interfaces;

interface CountryStateRepositoryInterface
{
    public function getAllStates();
    public function getCountryStates($country, array $fields = ['*']);
    public function getCountryStatesLike($country, $stateName, array $fields = ['*']);
//    public function deleteCountry($countryId);
    public function createState(array $stateDetails);
    public function updateState($stateId, array $newDetails);
}
