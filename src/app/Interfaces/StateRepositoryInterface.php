<?php

namespace App\Interfaces;

interface StateRepositoryInterface
{
    public function getAllStates();
    public function getCountryStates($countryId);
    public function getStatesLike($stateName);
//    public function deleteCountry($countryId);
    public function createState(array $stateDetails);
    public function updateState($stateId, array $newDetails);
}
