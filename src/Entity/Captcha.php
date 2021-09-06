<?php

namespace App\Entity;

use App\Repository\CaptchaRepository;
use Doctrine\ORM\Mapping as ORM;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity(repositoryClass=CaptchaRepository::class)
 */
class Captcha
{
    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     */
    private $discordId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $captchaId;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $captcha = false;

    /**
     * @return mixed
     */
    public function getDiscordId()
    {
        return $this->discordId;
    }

    /**
     * @param mixed $discordId
     */
    public function setDiscordId($discordId): void
    {
        $this->discordId = $discordId;
    }

    /**
     * @return mixed
     */
    public function getCaptchaId()
    {
        return $this->captchaId;
    }

    /**
     * @param mixed $captchaId
     */
    public function setCaptchaId($captchaId): void
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
