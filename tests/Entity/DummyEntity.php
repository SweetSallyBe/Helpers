<?php

namespace SweetSallyBe\Helpers\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use SweetSallyBe\Helpers\Entity\AbstractEntity;
use SweetSallyBe\Helpers\Tests\Repository\DummyRepository;

#[ORM\Entity(repositoryClass: DummyRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DummyEntity extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    public function getName(): string { return $this->name; }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}