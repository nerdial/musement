<?php

namespace App\Tests\Unit;

use App\Service\ApiService;
use App\Service\CityService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Cache\ItemInterface;

class CityServiceTest extends KernelTestCase
{
    protected ArrayCollection $cities;
    protected ArrayCollection $citiesWithForecast;
    protected ArrayCollection $citiesWithForecastCached;

    protected function setUp(): void
    {
        $this->cities = new ArrayCollection([
            [
                'name' => 'Amsterdam',
                'latitude' => 50.374,
                'longitude' => 4.9,
            ],
            [
                'name' => 'Paris',
                'latitude' => 51.374,
                'longitude' => 3.9,
            ],
        ]);

        $this->citiesWithForecast = new ArrayCollection([
            [
                'name' => 'Amsterdam',
                'days' => new ArrayCollection(['Moderate rain', 'Sunny']),
            ],
            [
                'name' => 'Paris',
                'days' => new ArrayCollection(['Sunny', 'Sunny']),
            ],
        ]);

        $this->citiesWithForecastCached = new ArrayCollection([
            [
                'name' => 'Tehran',
                'days' => new ArrayCollection(['Sunny', 'Sunny']),
            ],
            [
                'name' => 'London',
                'days' => new ArrayCollection(['Moderate rain', 'Sunny']),
            ],
        ]);

        parent::setUp();
    }

    public function testCallCitiesApiWithoutCache(): void
    {
        $kernel = self::bootKernel();

        $cache = $kernel->getContainer()->get('cache.app');

        $apiService = $this->createMock(ApiService::class);

        $apiService->expects($this->any())
            ->method('callCitiesApi')
            ->willReturn($this->cities);
        $apiService->method('callForecastApi')
            ->willReturn($this->citiesWithForecast);

        $cache->delete('cities');

        $cityService = new CityService($apiService, $cache);
        $actual = $cityService->getAllCitiesWithForecast();

        $this->assertInstanceOf(ArrayCollection::class, $actual);

        $this->assertNotEmpty($actual->toArray());

        $this->assertEquals($this->citiesWithForecast, $actual);
    }

    public function testCallCitiesApiWitCache(): void
    {
        $kernel = self::bootKernel();

        $cache = $kernel->getContainer()->get('cache.app');

        $apiService = $this->createMock(ApiService::class);

        $apiService->expects($this->any())
            ->method('callCitiesApi')
            ->willReturn($this->cities);
        $apiService->method('callForecastApi')
            ->willReturn($this->citiesWithForecast);

        $cache->delete('cities');

        $cache->get('cities', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            return $this->citiesWithForecastCached;
        });

        $cityService = new CityService($apiService, $cache);
        $actual = $cityService->getAllCitiesWithForecast();

        $this->assertInstanceOf(ArrayCollection::class, $actual);

        $this->assertNotEmpty($actual->toArray());

        $this->assertEquals($this->citiesWithForecastCached, $actual);
    }

    protected function tearDown(): void
    {
        $kernel = self::bootKernel();
        $cache = $kernel->getContainer()->get('cache.app');
        $cache->delete('cities');

        parent::tearDown();
    }
}
