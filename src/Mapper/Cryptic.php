<?php
namespace App\Mapper;

use Symfony\Component\Validator\Constraints as Assert;

class Cryptic{
    /**
     * @Assert\NotBlank
     *
     * @var ?string
     */
    private $key;
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     *
     * @var ?string
     */
    private $text;

    /**
     * @Assert\NotBlank
     *
     * @var ?string
     */
    private $checksum;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getChecksum(): string
    {
        return $this->checksum;
    }

    /**
     * @param string $checksum
     */
    public function setChecksum(string $checksum): void
    {
        $this->checksum = $checksum;
    }

}