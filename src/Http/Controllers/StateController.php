<?php

namespace Peergum\GeoDB\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Models\State;
use Peergum\GeoDB\Repositories\CountryStateRepository;

class StateController extends Controller
{
    private CountryStateRepository $stateRepository;

    public function __construct(CountryStateRepository $stateRepository)
    {
        $this->stateRepository = $stateRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Country $country, Request $request)
    {
        $filter = $request->get('like', '');

        if (!$filter) {
            switch ($request->get('type', 'all')) {
                case 'simple':
                    $fields = ['id', 'name'];
                    return response()->json($this->stateRepository->getCountryStates($country, $fields));
                    break;
                default:
                    $fields = ['*'];
                    return response()->json($this->stateRepository->getCountryStates($country, $fields));
            }
        }
        return response()->json($this->stateRepository->getCountryStatesLike($country, $filter));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStateRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country, State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStateRequest $request, State $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        //
    }
}
