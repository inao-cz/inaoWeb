<?php

namespace App\Entity;

use App\Repository\ApiKeyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: ApiKeyRepository::class)]
class ApiKey
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 254)]
    private $apiKey;

    #[ORM\OneToOne(inversedBy: "apiKey", targetEntity: User::class, cascade: ["persist", "remove"])]
    private User $user;

    #[ORM\OneToMany(mappedBy: "apiKey", targetEntity: Log::class)]
    private Collection $logs;

    #[Pure] public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setApiKey($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->logs->removeElement($log) && $log->getApiKey() === $this) {
            $log->setApiKey(null);
        }

        return $this;
    }
}
