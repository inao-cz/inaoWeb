<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "images"), ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: "date")]
    private DateTime $uploaded;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 10)]
    private string $ext;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $User): self
    {
        $this->user = $User;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getExt(): string
    {
        return $this->ext;
    }

    public function setExt(string $ext): void
    {
        $this->ext = $ext;
    }

    public function getUploaded(): DateTime
    {
        return $this->uploaded;
    }

    public function setUploaded(DateTime $uploaded): void
    {
        $this->uploaded = $uploaded;
    }

}
