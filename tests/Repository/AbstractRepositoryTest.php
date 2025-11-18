<?php

namespace SweetSallyBe\Helpers\Tests\Repository;

use Doctrine\ORM\Tools\SchemaTool;
use SweetSallyBe\Helpers\Tests\Entity\DummyEntity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractRepositoryTest extends KernelTestCase
{
    private DummyRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $em = self::getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());
        $this->repository = $em->getRepository(DummyEntity::class);
    }

    public function testSaveAndDelete(): void
    {
        $entity = new DummyEntity();
        $entity->setName('Test');

        $this->repository->save($entity);
        $this->assertNotNull($entity->getId());
        $this->assertNotNull($entity->getCreatedAt());
        $this->assertNotNull($entity->getUpdatedAt());

        $id = $entity->getId();

        $this->repository->delete($entity);

        $found = $this->repository->find($id);
        $this->assertNull($found);
    }

}
