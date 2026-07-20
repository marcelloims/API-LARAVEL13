<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UpdateWeatherCache extends Command
{
    protected $signature = 'weather:update-cache {city=Perth}';

    protected $description = 'Refresh weather cache for a city every hour';

    public function handle(): int
    {
        $city = strtolower(trim($this->argument('city')));
        $apiKey = config('services.openweather.key');
        $baseUrl = config('services.openweather.url');

        if (empty($apiKey) || empty($baseUrl)) {
            $this->error('OpenWeather configuration is missing.');
            return self::FAILURE;
        }

        $response = Http::get("{$baseUrl}/weather", [
            'q' => $city,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'id',
        ]);

        if (! $response->successful()) {
            $this->warn("Weather update failed for {$city}: {$response->status()}");
            return self::FAILURE;
        }

        $data = $response->json();
        $payload = [
            'success' => true,
            'message' => 'Data cuaca berhasil diambil.',
            'data' => [
                'city' => $data['name'],
                'country' => $data['sys']['country'],
                'temperature' => $data['main']['temp'],
                'condition' => ucfirst($data['weather'][0]['description']),
                'humidity' => $data['main']['humidity'],
                'icon_url' => "https://openweathermap.org/img/w/{$data['weather'][0]['icon']}.png",
            ],
        ];

        Cache::put('weather:' . $city, $payload, now()->addMinutes(15));

        $this->info("Weather cache refreshed for {$city}.");

        return self::SUCCESS;
    }
}
