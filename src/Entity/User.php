<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\NotBlank]
    private string $username;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank]
    private string $password;

    #[ORM\Column(type: "string")]
    #[Assert\Email, Assert\NotBlank, Assert\NotNull]
    private string $email;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Image::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToOne(mappedBy: "user", targetEntity: ApiKey::class, cascade: ["persist", "remove"])]
    private ApiKey $apiKey;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles($roles): self
    {
        if($roles === null){
            $this->roles = ['ROLE_USER'];
            return $this;
        }
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }


    public function eraseCredentials(): void
    {
    }

    public function getApiKey(): ApiKey
    {
        return $this->apiKey;
    }

    public function setApiKey(ApiKey $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier():string{
        return $this->getUsername();
    }

    public function __call(string $name, array $arguments)
    {
        return $this->getUsername();
    }
}
