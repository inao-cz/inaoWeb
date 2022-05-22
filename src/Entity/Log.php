<?php

namespace App\Entity;

use App\Repository\LogRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "datetime")]
    private DateTime $date;

    #[ORM\Column(type: "text")]
    private mixed $action;

    #[ORM\ManyToOne(targetEntity: ApiKey::class, inversedBy: "logs"), ORM\JoinColumn(nullable: false)]
    private ApiKey $apiKey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getApiKey(): ApiKey
    {
        return $this->apiKey;
    }

    public function setApiKey(ApiKey $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
