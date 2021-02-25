<?php

namespace SweetSallyBe\Helpers\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    protected ?\DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected ?\DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedAtFormatted(): string
    {
        if ($this->getCreatedAt()) {
            return $this->getCreatedAt()->format('d/m/Y');
        }
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getUpdatedAtFormatted(): string
    {
        if ($this->getUpdatedAt()) {
            return $this->getUpdatedAt()->format('d/m/Y');
        }
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    protected static function findConstants(string $search, $className = null): array
    {
        $className = ($className == null) ? __CLASS__ : $className;
        $oClass = new \ReflectionClass($className);
        $constants = $oClass->getConstants();
        $results = [];
        foreach ($constants as $constantName => $constantValue) {
            if (strpos($constantName, $search) === 0) {
                $results[$constantValue] = substr($constantName, strlen($search));
            }
        }
        return $results;
    }
}
