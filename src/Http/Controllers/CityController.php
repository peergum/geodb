<?php

namespace Peergum\GeoDB\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PeerGum\GeoDB\Models\City;
use Inertia\Inertia;
use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Repositories\CountryCityRepository;

class CityController extends Controller
{
    private CountryCityRepository $countryCityRepository;

    public function __construct(CountryCityRepository $countryCityRepository)
    {
        $this->countryCityRepository = $countryCityRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('like', null);
        $country = $request->get('country',0);
        switch ($request->get('type', 'all')) {
            case 'simple':
                $fields = ['id', 'cc', 'name', 'states','countries.*'];
                break;
            default:
                $fields = ['*'];
                return response()->json($this->countryCityRepository->getCountryCities($country, $fields));
        }
        if (!$filter) {
            return response()->json($this->countryCityRepository->getCountryCities($country, $fields));
        } else {
            return response()->json($this->countryCityRepository->getCitiesLike($filter, $country, ['*']));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public
    function store(StoreCityRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public
    function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(UpdateCityRequest $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(City $city)
    {
        //
    }
}
