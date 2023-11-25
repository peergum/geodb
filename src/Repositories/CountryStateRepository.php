<?php

namespace Peergum\GeoDB\Repositories;

use Peergum\GeoDB\Interfaces\CountryStateRepositoryInterface;
use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Models\State;

class CountryStateRepository implements CountryStateRepositoryInterface
{
    public function getAllStates()
    {
        return State::all()->get();
    }

    public function getCountryStates($country, array $fields = ['*'])
    {
        return State::where('country_id', '=', $country->id)
            ->orderBy('name', 'asc')
            ->get($fields);
    }

    public function getCountryStatesLike($country, $stateName, array $fields = ['*'])
    {
        return State::where('country_id', '=', $country->id)
            ->where('name', 'like', $stateName)
            ->orderBy('name', 'asc')
            ->get();
    }

    public function createState(array $stateDetails)
    {
        State::create($stateDetails);
    }

    public function updateState($stateId, array $newDetails)
    {
        State::findOrFail($stateId)->first()->update($newDetails);
    }
}
