<?php

namespace App\Entity;

use App\Repository\LinksRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LinksRepository::class)]
class Links
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "text")]
    private string $name;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank, Assert\NotNull]
    private string $target;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    #[ORM\Column(type: "boolean", nullable: true)]
    private ?bool $public;

    #[ORM\Column(type: "string", length: 255)]
    private string $creator;

    #[ORM\Column(type: "integer")]
    private int $redirects;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTarget(): ?string
    {
        return $this->target;
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function setCreator(string $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getRedirects(): int
    {
        return $this->redirects;
    }

    public function setRedirects(int $redirects): void
    {
        $this->redirects = $redirects;
    }

    public function addRedirect(): void
    {
        ++$this->redirects;
    }
}
