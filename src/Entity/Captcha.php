<?php

namespace App\Entity;

use App\Repository\CaptchaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaptchaRepository::class)]
class Captcha
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'bigint')]
    private $discordId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $captchaId;

    #[ORM\Column(type: 'boolean')]
    private bool $solved;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscordId(): ?string
    {
        return $this->discordId;
    }

    public function setDiscordId(string $discordId): self
    {
        $this->discordId = $discordId;

        return $this;
    }

    public function getCaptchaId(): ?string
    {
        return $this->captchaId;
    }

    public function setCaptchaId(string $captchaId): self
    {
        $this->captchaId = $captchaId;

        return $this;
    }

    public function getSolved(): bool
    {
        return $this->solved;
    }

    public function setSolved(bool $solved): self
    {
        $this->solved = $solved;

        return $this;
    }
}
