<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WeatherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @codeCoverageIgnore
 */
#[ORM\Entity(repositoryClass: WeatherRepository::class)]
#[ApiResource(
    collectionOperations: ['POST'],
    itemOperations: [],
)]
class Weather
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'date')]
    private $forecastDate;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'weather')]
    #[ORM\JoinColumn(nullable: false)]
    private $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getForecastDate(): ?\DateTimeInterface
    {
        return $this->forecastDate;
    }

    public function setForecastDate(\DateTimeInterface $forecastDate): self
    {
        $this->forecastDate = $forecastDate;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
