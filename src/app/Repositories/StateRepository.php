<?php

namespace App\Repositories;

use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\StateRepositoryInterface;
use App\Models\Country;
use App\Models\State;

class StateRepository implements StateRepositoryInterface
{
    public function getAllStates() {
        return State::all();
    }

    public function getCountryStates($countryId)
    {
        return State::where('country_id','=',$countryId);
    }

    public function getStatesLike($stateName) {
        return State::where('name','like', $stateName);
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
