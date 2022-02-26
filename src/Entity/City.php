<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\WeatherController;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @codeCoverageIgnore
 */
#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: [
        'get',
        'get_weather' => [
            'openapi_context' => [
                'summary' => 'Retrieves a weather for a city filtered by today, 
                tomorrow, or days in number ',
                'parameters' => [
                    [
                        'name' => 'day',
                        'type' => 'string',
                        'in' => 'path',
                        'required' => true,
                        'description' => 'today, tomorrow, or days in number',
                    ],
                ],
            ],
            'method' => 'get',
            'path' => '/cities/{id}/weathers/{day}',
            'read' => false,
            'controller' => WeatherController::class,
        ],
    ],
)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Weather::class, orphanRemoval: true)]
    private $weathers;

    public function __construct()
    {
        $this->weathers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Weather>
     */
    public function getWeathers(): \Doctrine\Common\Collections\Collection
    {
        return $this->weathers;
    }

    public function addWeather(Weather $weather): self
    {
        if (!$this->weathers->contains($weather)) {
            $this->weathers[] = $weather;
            $weather->setCity($this);
        }

        return $this;
    }

    public function removeWeather(Weather $weather): self
    {
        if ($this->weathers->removeElement($weather)) {
            // set the owning side to null (unless already changed)
            if ($weather->getCity() === $this) {
                $weather->setCity(null);
            }
        }

        return $this;
    }
}
