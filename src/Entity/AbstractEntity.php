<?php

namespace SweetSallyBe\Helpers\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PropertyAccess\PropertyAccess;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    protected ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedAtFormatted(): string
    {
        if ($this->getCreatedAt()) {
            return $this->getCreatedAt()->format('d/m/Y');
        }

        return '';
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getUpdatedAtFormatted(): string
    {
        if ($this->getUpdatedAt()) {
            return $this->getUpdatedAt()->format('d/m/Y');
        }

        return '';
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable();
        $this->createdAt ??= $now;
        $this->updatedAt ??= $now;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function setStartValues(array $startValues): self
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($startValues as $key => $value) {
            if ($accessor->isWritable($this, $key)) {
                $accessor->setValue($this, $key, $value);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $name => $value) {
            switch (true) {
                case is_scalar($value) || $value === null:
                    $data[$name] = $value;
                    break;

                case $value instanceof \DateTimeInterface:
                    // Gebruik ISO-8601 formaat voor consistentie
                    $data[$name] = $value->format(\DateTimeInterface::ATOM);
                    break;

                case is_array($value):
                    // Recursief serialiseren van arrays
                    $data[$name] = array_map(function ($item) {
                        if (is_scalar($item) || $item === null) {
                            return $item;
                        }
                        if ($item instanceof \DateTimeInterface) {
                            return $item->format(\DateTimeInterface::ATOM);
                        }
                        if (is_object($item) && method_exists($item, 'toArray')) {
                            return $item->toArray();
                        }

                        return (string)$item;
                    }, $value);
                    break;

                case is_object($value) && method_exists($value, 'toArray'):
                    // Sub-entity of DTO die zelf toArray heeft
                    $data[$name] = $value->toArray();
                    break;

                default:
                    // Onbekend type → veilig string casten
                    $data[$name] = (string)$value;
            }
        }

        return $data;
    }
}