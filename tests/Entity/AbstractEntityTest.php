<?php

namespace SweetSallyBe\Helpers\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SweetSallyBe\Helpers\Entity\AbstractEntity;

class DummyObject extends AbstractEntity
{
}

final class AbstractEntityTest extends TestCase
{
    public function testConstruction(): void
    {
        $object = new DummyObject();
        $now = new \DateTimeImmutable();
        $this->assertInstanceOf(AbstractEntity::class, $object);
        $object->setUpdatedAt($now);
        $this->assertInstanceOf(\DateTimeInterface::class, $object->getUpdatedAt());
        $this->assertEquals($now, $object->getUpdatedAt());
    }

    public function testSetStartValues()
    {
        $object = new DummyObject();
        $now = new \DateTimeImmutable();
        $object->setstartValues(['updatedAt' => $now]);
        $this->assertEquals($now, $object->getUpdatedAt());
    }

    public function testToArray(): void
    {
        $entity = new DummyObject();
        $now = new \DateTimeImmutable();
        $entity->setUpdatedAt($now);
        $array = $entity->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('createdAt', $array);
        $this->assertArrayHasKey('updatedAt', $array);
        $this->assertEquals($now->format(DATE_ATOM), $array['updatedAt']);
    }

}
