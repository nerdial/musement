<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    public function __construct(
        private HttpClientInterface $client,
        private UrlService $urlService
    ) {
    }

    public function callCitiesApi(): ArrayCollection
    {
        $response = $this->client->request('GET', $this->urlService->getCitiesUrl());

        $cities = json_decode($response->getContent(), true);
        $collection = new ArrayCollection($cities);

        return $collection->map(function ($item) {
            return [
                'name' => $item['name'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
            ];
        });
    }

    public function callForecastApi(ArrayCollection $cities): ArrayCollection
    {
        return $cities->map(function ($item) {
            $cityName = $item['name'];
            $latitude = $item['latitude'];
            $longitude = $item['longitude'];
            $forecastData = $this->forecast($latitude, $longitude);

            return [
                'name' => $cityName,
                'days' => $forecastData->map(function ($item) {
                    return $item['day']['condition']['text'];
                }),
            ];
        });
    }

    private function forecast($latitude, $longitude): ArrayCollection
    {
        $apiUrl = $this->urlService->getWeatherUrl($latitude, $longitude);
        $response = $this->client->request('GET', $apiUrl);
        $data = json_decode($response->getContent(), true);

        return new ArrayCollection($data['forecast']['forecastday']);
    }
}
