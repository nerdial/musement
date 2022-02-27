<?php

namespace App\Tests\Unit;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Contracts\Cache\ItemInterface;

class CrawlerCommandTest extends KernelTestCase
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

    public function testIfCommandPrintsCorrectly(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $cache = $kernel->getContainer()->get('cache.app');

        $cache->delete('cities');

        $cache->get('cities', function (ItemInterface $item) {
            return $this->citiesWithForecastCached;
        });

        $command = $application->find('app:crawl');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Processed city Tehran | Sunny - Sunny', $output);

        $this->assertStringContainsString('Processed city London | Moderate rain - Sunny',
            $output);

        $commandTester->assertCommandIsSuccessful();
    }

    protected function tearDown(): void
    {
        $kernel = self::bootKernel();
        $cache = $kernel->getContainer()->get('cache.app');
        $cache->delete('cities');

        parent::tearDown();
    }
}
