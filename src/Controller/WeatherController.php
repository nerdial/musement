<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

/**
 * @codeCoverageIgnore
 */
#[AsController]
class WeatherController extends AbstractController
{
    public function __invoke(string $id, string $day)
    {
        // return proper weather for a city
        return new JsonResponse([
            'id' => $id,
            'weather' => 'Sunny',
        ]);
    }
}
