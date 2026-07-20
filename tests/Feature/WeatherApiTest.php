<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherApiTest extends TestCase
{
    use RefreshDatabase;
    public function test_weather_index_returns_formatted_data(): void
    {
        config(['services.openweather.url' => 'https://api.openweathermap.org/data/2.5']);
        config(['services.openweather.key' => 'fake-key']);

        Http::fake([
            'https://api.openweathermap.org/data/2.5/*' => Http::response([
                'name' => 'Bandung',
                'sys' => ['country' => 'ID'],
                'main' => [
                    'temp' => 22.5,
                    'humidity' => 85,
                ],
                'weather' => [
                    [
                        'description' => 'hujan rintik',
                        'icon' => '10d',
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/weather/index?city=Bandung');

        $response->assertStatus(200)
            ->assertJsonPath('data.city', 'Bandung')
            ->assertJsonPath('data.country', 'ID')
            ->assertJsonPath('data.temperature', 22.5)
            ->assertJsonPath('data.condition', 'Hujan rintik')
            ->assertJsonPath('data.humidity', 85);
    }

    public function test_weather_index_handles_api_errors(): void
    {
        config(['services.openweather.url' => 'https://api.openweathermap.org/data/2.5']);
        config(['services.openweather.key' => 'fake-key']);

        Http::fake([
            'https://api.openweathermap.org/data/2.5/*' => Http::response([
                'cod' => '404',
                'message' => 'city not found',
            ], 404),
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/weather/index?city=KotaAtlantis');

        $response->assertStatus(404)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Kota tidak ditemukan atau terjadi kesalahan server.');
    }

    public function test_weather_data_is_cached_for_subsequent_requests(): void
    {
        config(['services.openweather.url' => 'https://api.openweathermap.org/data/2.5']);
        config(['services.openweather.key' => 'fake-key']);

        Http::fake([
            'https://api.openweathermap.org/data/2.5/*' => Http::sequence()
                ->push([
                    'name' => 'Bandung',
                    'sys' => ['country' => 'ID'],
                    'main' => ['temp' => 22.5, 'humidity' => 85],
                    'weather' => [['description' => 'hujan rintik', 'icon' => '10d']],
                ], 200)
                ->push([
                    'name' => 'Jakarta',
                    'sys' => ['country' => 'ID'],
                    'main' => ['temp' => 30, 'humidity' => 70],
                    'weather' => [['description' => 'cerah', 'icon' => '01d']],
                ], 200),
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $first = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/weather/index?city=Bandung');

        $second = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/weather/index?city=Bandung');

        $first->assertStatus(200)
            ->assertJsonPath('data.city', 'Bandung');

        $second->assertStatus(200)
            ->assertJsonPath('data.city', 'Bandung');

        Http::assertSentCount(1);
    }
}
