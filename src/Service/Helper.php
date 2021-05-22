<?php


namespace SweetSallyBe\Helpers\Service;


use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class Helper
{
    private ?KernelInterface $kernel = null;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getConfig(string $config): ?array
    {
        $menuFile = $this->kernel->getProjectDir() . '/config/services/' . $config . '.yaml';
        if (!file_exists($menuFile)) {
            throw new FileNotFoundException('Invalid Menufile: ' . $config);
        }
        return Yaml::parseFile($menuFile);
    }

    public static function getConstants(string $search, string $className): array
    {
        $oClass = new \ReflectionClass($className);
        $constants = $oClass->getConstants();
        $results = [];
        foreach ($constants as $constantName => $constantValue) {
            if (strpos($constantName, $search) === 0) {
                $results[$constantValue] = substr($constantName, strlen($search));
            }
        }
        return $results;
    }
}