<?php

namespace App\Entity;

use App\Repository\InviteRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InviteRepository::class)]
class Invite
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 128)]
    private string $code;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ["persist", "remove"])]
    private User $user;

    #[ORM\Column(type: "datetime")]
    private DateTime $validUntil;

    #[ORM\Column(type: "json", nullable: true)]
    private array $roles = [];

    #[ORM\Column(type: "text")]
    private mixed $email;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ["persist", "remove"])]
    private ?User $usedBy;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }


    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValidUntil(): DateTime
    {
        return $this->validUntil;
    }

    public function setValidUntil(DateTime $validUntil): self
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsedBy(): ?User
    {
        return $this->usedBy;
    }

    public function setUsedBy(?User $usedBy): self
    {
        $this->usedBy = $usedBy;

        return $this;
    }
}
