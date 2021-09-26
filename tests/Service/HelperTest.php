<?php

namespace App\Tests\Service;

use SweetSallyBe\Helpers\Service\Helper;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class HelperTest extends KernelTestCase
{
    private ?Helper $helperService = null;

    protected function setUp(): void
    {
        $container = $this->getContainer();
        $this->helperService = new Helper(
            $container->get(KernelInterface::class),
            $container->get(ParameterBagInterface::class)
        );
    }

    /**
     * @test
     */
    public function config(): void
    {
        $config = $this->helperService->getConfig('helper-test');
        $this->assertIsArray($config);
    }

    /**
     * @test
     */
    public function constants(): void
    {
        $this->assertIsArray($this->helperService::getConstants('PARAM_', Helper::class));
    }

    /**
     * @test
     */
    public function definedLanguages(): void
    {
        $this->assertIsArray($this->helperService->getDefinedLanguages());
    }

    /**
     * @test
     */
    public function defaultLanguage(): void
    {
        $this->assertEquals('en', $this->helperService->getDefaultLanguage());
    }
}
