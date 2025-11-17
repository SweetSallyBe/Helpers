<?php

namespace SweetSallyBe\Helpers\Tests\Service;

use SweetSallyBe\Helpers\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HelperTest extends KernelTestCase
{
    private static ?Helper $service = null;

    protected function setUp(): void
    {
        self::bootKernel();
        $parameterBag = self::getContainer()->get('parameter_bag');
        $kernel = self::$kernel;
        self::$service = new Helper($kernel, $parameterBag);
    }

    public function testSetup()
    {
        $this->assertInstanceOf(Helper::class, self::$service);
    }

    public function testConfig(): void
    {
        $config = self::$service->getConfig('helper-test');
        $this->assertIsArray($config);
    }

    public function testConstants(): void
    {
        $this->assertIsArray(self::$service->getConstants('PARAM_', Helper::class));
        $this->assertGreaterThan(0, count(self::$service->getConstants('PARAM_', Helper::class)));
    }

    public function testGetDefinedLanguages(): void
    {
        $this->assertIsArray(self::$service->getDefinedLanguages());
    }

    public function testGetDefaultLanguage(): void
    {
        $this->assertEquals('en', self::$service->getDefaultLanguage());
    }
}
