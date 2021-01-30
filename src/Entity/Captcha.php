<?php

namespace App\Entity;

use App\Repository\CaptchaRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CaptchaRepository::class)
 */
class Captcha
{
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $discordId;

    /**
     * @var null|DateTime
     * @ORM\Column(type="date")
     */
    private $dateCreated;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $captchaId;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isSolved;

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
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated): void
    {
        $this->dateCreated = $dateCreated;
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

    /**
     * @return bool
     */
    public function isSolved(): bool
    {
        return $this->isSolved;
    }

    /**
     * @param bool $isSolved
     */
    public function setIsSolved(bool $isSolved): void
    {
        $this->isSolved = $isSolved;
    }


}
