<?php

namespace Peergum\GeoDB\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Peergum\GeoDB\Models\Country;
use Peergum\GeoDB\Repositories\CountryRepository;

class CountryController extends Controller
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('like', '');

        if (!$filter) {
            switch ($request->get('type', 'all')) {
                case 'simple':
                    $fields = ['id', 'cc', 'name'];
                    return response()->json($this->countryRepository->getAllCountries($fields));
                    break;
                default:
                    $fields = ['*'];
                    return response()->json($this->countryRepository->getAllCountries($fields));
            }
        }
        return response()->json($this->countryRepository->getCountriesLike($filter));
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
    public function store(StoreCountryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $country)
    {
        return response()->json($this->countryRepository->getCountryById($country));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        //
    }
}
