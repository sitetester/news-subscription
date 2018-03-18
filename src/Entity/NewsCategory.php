<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class NewsCategory
{
    /**
     * @var int
     */
    private $id;

    /**
     * @Assert\NotBlank(
     *     message = "Choose a valid category title."
     * )
     */
    private $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    // not needed in case of Doctrine

    public function setTitle(string $title): NewsCategory
    {
        $this->title = $title;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): NewsCategory
    {
        $this->id = $id;

        return $this;
    }
}