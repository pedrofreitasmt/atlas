<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Services\CityService;
use Inertia\Inertia;
use Inertia\Response;

class CityController extends Controller
{
    public function __construct(private CityService $cityService)
    {
    }

    public function index(): Response
    {
        $cities = City::with('state')->paginate(5);

        return Inertia::render('cities/Index', compact('cities'));
    }

    public function show(City $city): Response
    {
        $cityData = $this->cityService->getCityInfo($city->ibge);

        return Inertia::render('cities/Show', compact('city', 'cityData'));
    }
}
