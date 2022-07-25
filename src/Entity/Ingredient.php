<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups(['index', 'details'])]
    private int $id;

    #[ORM\Column(type: 'text')]
    #[Groups(['index', 'details'])]
    private string $name;

    #[ORM\Column(type: 'integer')]
    #[Groups(['index', 'details'])]
    private int $price;

    #[ORM\Column(type: 'integer')]
    #[Groups(['index', 'details'])]
    private int $weight;

    #[ORM\Column(type: 'datetime')]
    protected DateTimeInterface $created;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $updated;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->updated = new DateTime();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdated(new DateTime());
        if (!$this->getCreated()) {
            $this->setCreated(new DateTime());
        }
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
