<?php

namespace App\Tests\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Cache\ItemInterface;

class CityControllerTest extends WebTestCase
{

    protected ArrayCollection $citiesWithForecastCached;

    public function setUp(): void
    {
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

    public function testCityList(): void
    {
        $client = static::createClient();

        $client->getContainer();
        $cache = $client->getContainer()->get('cache.app');

        $cache->delete('cities');

        $cache->get('cities', function (ItemInterface $item) {
            return $this->citiesWithForecastCached;
        });

        $client->request('GET', '/city');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Weather App');

        $this->assertSelectorTextContains('td', 'Tehran');
        $this->assertSelectorTextNotContains('td', 'Amsterdam');

    }
}
