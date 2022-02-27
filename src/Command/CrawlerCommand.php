<?php

namespace App\Command;

use App\Service\CityService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlerCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:crawl';

    public function __construct(private CityService $cityService, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command calls forecast api 
            and print the weather of each city into stdout')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cities = $this->cityService->getAllCitiesWithForecast();

        foreach ($cities as $city) {
            $cityName = $city['name'];
            $today = $city['days'][0];
            $tomorrow = $city['days'][1];
            $output->writeln("<info>Processed city {$cityName} | {$today} - {$tomorrow} </>");
            $output->writeln('-------------------------------------------------------');
        }

        return Command::SUCCESS;
    }
}
