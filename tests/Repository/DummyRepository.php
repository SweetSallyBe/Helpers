<?php

namespace SweetSallyBe\Helpers\Tests\Repository;

use Doctrine\Persistence\ManagerRegistry;
use SweetSallyBe\Helpers\Repository\AbstractRepository;
use SweetSallyBe\Helpers\Tests\Entity\DummyEntity;

class DummyRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyEntity::class);
    }
}