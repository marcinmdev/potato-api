<?php

namespace App\Entity;

use App\Repository\MealRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MealRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups(['index', 'details'])]
    private int $id;

    #[ORM\Column(type: 'text')]
    #[Groups(['index', 'details'])]
    private string $name;

    #[ORM\Column(type: 'datetime')]
    protected DateTimeInterface $created;

    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $updated;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\ManyToMany(targetEntity: Ingredient::class, cascade: ['persist'])]
    #[Groups(['index', 'details'])]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
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

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
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
