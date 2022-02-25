<?php

namespace App\Tests\Unit;

use App\Service\UrlService;
use PHPUnit\Framework\TestCase;

class UrlServiceTest extends TestCase
{
    public function testCityUrlGenerator(): void
    {
        $urlService = new UrlService();

        $expected = 'https://api.musement.com/api/v3/cities/';

        $actual = $urlService->getCitiesUrl();

        $this->assertSame($expected, $actual);

    }

    public function testWeatherUrlGenerator(): void
    {
        $urlService = new UrlService();

        $baseUrl = $_ENV['WEATHER_API_URL'];
        $token = $_ENV['WEATHER_API_TOKEN'];

        $latitude = 34.5;
        $longitude = 51.33;
        $days = 2;

        $expected = "$baseUrl/v1/forecast.json?key=$token&q=$latitude,$longitude &days=$days";

        $actual = $urlService->getWeatherUrl($latitude, $longitude, $days);

        $this->assertSame($expected, $actual);

    }
}
