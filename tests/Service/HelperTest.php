<?php

namespace SweetSallyBe\Tests\Service;

use SweetSallyBe\Helpers\Service\Helper;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HelperTest extends KernelTestCase
{
    private ?Helper $helperService = null;
    private static Helper $helperServiceStatic;

    protected function setUp(): void
    {
        $this->helperService = $this->getContainer()->get(Helper::class);
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
