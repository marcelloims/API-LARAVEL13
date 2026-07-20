<?php

namespace App\Http\Controllers\Weather;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    /**
     * Get current weather data dynamically by city name or coordinates (lat, lon).
     */
    public function show(Request $request)
    {
        // Validasi input
        $request->validate([
            'city' => 'sometimes|string|max:255'
        ]);

        $city = $request->input('city', 'Jakarta');
        $apiKey = config('services.openweather.key');
        $baseUrl = config('services.openweather.url');

        $response = Http::get("{$baseUrl}/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'id'
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Format response JSON yang rapi
            return response()->json([
                'success' => true,
                'message' => 'Data cuaca berhasil diambil.',
                'data' => [
                    'city' => $data['name'],
                    'country' => $data['sys']['country'],
                    'temperature' => $data['main']['temp'],
                    'condition' => ucfirst($data['weather'][0]['description']),
                    'humidity' => $data['main']['humidity'],
                    'icon_url' => "https://openweathermap.org/img/w/{$data['weather'][0]['icon']}.png"
                ]
            ], 200);
        }

        // Response JSON jika kota tidak ditemukan / error
        return response()->json([
            'success' => false,
            'message' => 'Kota tidak ditemukan atau terjadi kesalahan server.',
        ], $response->status()); // Mengembalikan status code HTTP asli (misal: 404)
    }
}
