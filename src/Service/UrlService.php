<?php

namespace App\Service;

class UrlService
{
    public function getCitiesUrl(): string
    {
        $baseUrl = $_ENV['MUSEMENT_API_URL'];

        return "$baseUrl/api/v3/cities/";
    }

    public function getWeatherUrl(string $latitude, string $longitude, int $days = 2): string
    {
        $baseUrl = $_ENV['WEATHER_API_URL'];
        $token = $_ENV['WEATHER_API_TOKEN'];

        return "$baseUrl/v1/forecast.json?key=$token&q=$latitude,$longitude &days=$days";
    }
}
