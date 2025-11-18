<?php

namespace SweetSallyBe\Helpers\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SweetSallyBe\Helpers\Entity\AbstractEntity;
use SweetSallyBe\Helpers\Entity\Interfaces\Token as TokenInterface;
use SweetSallyBe\Helpers\Entity\Traits\Token as TokenTrait;

class DummyObjectToken extends AbstractEntity implements TokenInterface
{
    use TokenTrait;

    private string $token = '';
}

final class AbstractEntityTokenTest extends TestCase
{
    public function testConstruction(): void
    {
        $object = new DummyObjectToken();
        $now = new \DateTimeImmutable();
        $this->assertInstanceOf(AbstractEntity::class, $object);
        $object->setUpdatedAt($now);
        $object->updateToken();
        $this->assertInstanceOf(\DateTimeInterface::class, $object->getUpdatedAt());
        $this->assertEquals($now, $object->getUpdatedAt());
        $this->assertNotEmpty($object->getToken());
    }
}
