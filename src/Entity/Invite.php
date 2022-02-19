<?php

namespace App\Entity;

use App\Repository\InviteRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InviteRepository::class)]
class Invite
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private $id;

    /**
     * @var mixed
     */
    #[ORM\Column(type: "string", length: 128)]
    private $code;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ["persist", "remove"])]
    private $user;

    #[ORM\Column(type: "datetime")]
    private $validUntil;

    #[ORM\Column(type: "json", nullable: true)]
    private $roles = [];

    #[ORM\Column(type: "text")]
    private $email;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ["persist", "remove"])]
    private $usedBy;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     */
    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getValidUntil(): ?DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(DateTimeInterface $validUntil): self
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getEmail(): ?string
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
