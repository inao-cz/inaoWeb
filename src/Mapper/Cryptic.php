<?php
namespace App\Mapper;

use Symfony\Component\Validator\Constraints as Assert;

class Cryptic{
    /**
     * @Assert\NotBlank
     *
     * @var ?string
     */
    private ?string $key;
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     *
     * @var ?string
     */
    private ?string $text;

    /**
     * @Assert\NotBlank
     *
     * @var ?string
     */
    private ?string $checksum;

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function setChecksum(string $checksum): void
    {
        $this->checksum = $checksum;
    }

}