<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use inertia\Inertia;
use Illuminate\Support\Facades\Http;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/weather', function(){
    return  inertia::render('Weather');
});

Route::get('/weather', function(Request $request){
    $lat = $request->input('lat', '22.689482');
    $lon = $request->input('lon', '120.29529');

    $openWeatherKey = config('services.openweather.key');
    if($lat && $lon){
        $weatherResponse = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat' => $lat,
            'lon' => $lon,
            "appid" => $openWeatherKey,
            "units" => 'metric',
        ]);
        return $weatherResponse->json();
    }else{
        return response()->json(['error' => 'Coordinates not provided.'], 400);
    }
});

Route::get('/forecast', function(Request $request){
    $lat = $request->input('lat', '22.689482');
    $lon = $request->input('lon', '120.29529');

    $openWeatherKey = config('services.openweather.key');

    if($lat && $lon){
        $forecastResponse = Http::get('api.openweathermap.org/data/2.5/forecast', [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $openWeatherKey,
            'units' => 'metric'
        ]);
        return $forecastResponse->json();
    }else{
        return response()->json(['error' => 'Coordinates not provided.'], 400);
    }
});
