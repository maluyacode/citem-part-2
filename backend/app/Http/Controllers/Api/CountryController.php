<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        Log::info("TESTTT");
        $countries = [
            'Philippines',
            'United States',
            'Canada',
            'United Kingdom',
            'Australia',
            'Japan',
            'China',
            'Germany',
            'France',
            'Singapore',
        ];

        return response()->json($countries);
    }
}
