<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CityService
{
    public function getCityInfo(int $ibgeCode): ?array
    {
        $url = "https://servicodados.ibge.gov.br/api/v1/localidades/municipios/{$ibgeCode}";

        $response = Http::get($url);

        return $response->successful() ? $response->json() : null;
    }
}
