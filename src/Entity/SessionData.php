<?php

namespace App\Entity;

use App\Repository\SessionDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionDataRepository::class)]
class SessionData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $session = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'session_data')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private ?int $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?string
    {
        return $this->session;
    }

    public function setSession(string $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }
}
