<?php

namespace App\Entity;

use App\Repository\CaptchaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaptchaRepository::class)]
class Captcha
{
    #[ORM\Id, ORM\Column(type: "bigint")]
    private mixed $discordId;

    #[ORM\Column(type: "string", length: 255)]
    private mixed $captchaId;

    #[ORM\Column(type: "boolean")]
    private bool $captcha = false;

    /**
     * @return mixed
     */
    public function getDiscordId(): mixed
    {
        return $this->discordId;
    }

    /**
     * @param mixed $discordId
     */
    public function setDiscordId(mixed $discordId): void
    {
        $this->discordId = $discordId;
    }

    /**
     * @return mixed
     */
    public function getCaptchaId(): mixed
    {
        return $this->captchaId;
    }

    /**
     * @param mixed $captchaId
     */
    public function setCaptchaId(mixed $captchaId): void
    {
        $this->captchaId = $captchaId;
    }

    public function isCaptcha(): bool
    {
        return $this->captcha;
    }

    public function setCaptcha(bool $captcha): void
    {
        $this->captcha = $captcha;
    }

}
