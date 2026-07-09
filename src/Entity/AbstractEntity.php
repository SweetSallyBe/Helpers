<?php

namespace SweetSallyBe\Helpers\Entity;

use Doctrine\ORM\Mapping as ORM;
use SweetSallyBe\Helpers\Entity\Interfaces\IsAbstractEntityInterface;
use SweetSallyBe\Helpers\Entity\Traits\IsAbstractEntityTrait;

#[ORM\MappedSuperclass]
#[ORM\HasLifecycleCallbacks]
abstract class AbstractEntity implements IsAbstractEntityInterface
{
    use IsAbstractEntityTrait;
}