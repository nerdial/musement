<?php

namespace App\Tests\Unit;

use App\Service\ApiService;
use App\Service\UrlService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * @internal
 * @coversNothing
 */
class ApiServiceTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCallCitiesApi(): void
    {
        $kernel = self::bootKernel();

        $jsonFilePath = $kernel->getProjectDir().'/data/cities.json';

        $content = file_get_contents($jsonFilePath);

        $firstItem = json_decode($content, true)[0];

        $expectedFirstItem = [
            'name' => $firstItem['name'],
            'latitude' => $firstItem['latitude'],
            'longitude' => $firstItem['longitude'],
        ];

        $responses = [
            new MockResponse($content, []),
        ];

        $client = new MockHttpClient($responses);

        $urlService = new UrlService();

        $apiService = new ApiService($client, $urlService);
        $cities = $apiService->callCitiesApi();

        $actualFirstItem = $cities[0];

        $this->assertInstanceOf(ArrayCollection::class, $cities);

        $this->assertEquals($expectedFirstItem, $actualFirstItem);

        $this->assertNotEmpty($cities->toArray());
    }

    public function testCallForecastApi(): void
    {
        $kernel = self::bootKernel();

        $jsonFilePath = $kernel->getProjectDir().'/data/forecast.json';

        $content = file_get_contents($jsonFilePath);

        $responses = [
            new MockResponse($content, []),
            new MockResponse($content, []),
            new MockResponse($content, []),
            new MockResponse($content, []),
        ];

        $client = new MockHttpClient($responses);

        $urlService = new UrlService();

        $cities = new ArrayCollection([
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

        $days = ['Moderate rain', 'Sunny'];

        $expected = new ArrayCollection();
        foreach ($cities as $city) {
            $expected->add([
                'name' => $city['name'],
                'days' => new ArrayCollection($days),
            ]);
        }

        $apiService = new ApiService($client, $urlService);
        $cities = $apiService->callForecastApi($cities);

        $this->assertInstanceOf(ArrayCollection::class, $cities);

        $this->assertNotEmpty($cities->toArray());

        $this->assertEquals($expected->toArray(), $cities->toArray());
    }
}
