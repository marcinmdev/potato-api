<?php

namespace App\Entity;

use App\Repository\UserAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAccountRepository::class)]
class UserAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'text', unique: true, nullable: true)]
    private string $apiToken;

    public function getApiToken(): string
    {
        return $this->apiToken;
    }

    public function setApiToken($apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }
}
