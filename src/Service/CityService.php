<?php

namespace App\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CityService
{
    public function __construct(
        private ApiService $apiService,
        private CacheInterface $cache
    ) {
    }

    public function getAllCitiesWithForecast(): ArrayCollection
    {
        // caching the heavy process and store it for 10 minutes
        return $this->cache->get('cities', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            // I am using collection here, in this case it fits perfectly
            $cities = $this->apiService->callCitiesApi();

            return $this->apiService->callForecastApi($cities);
        });
    }
}
