<?php

namespace App\Controller;

use App\Service\CityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    public function __construct(
        private CityService $cityService
    ) {
    }

    #[Route('/city', name: 'city')]
    public function index(): Response
    {
        $citiesWithWeathers = $this->cityService->getAllCitiesWithForecast();

        return $this->render('city/index.html.twig', [
            'cities' => $citiesWithWeathers,
        ]);
    }
}
