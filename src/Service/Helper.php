<?php


namespace SweetSallyBe\Helpers\Service;


use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Yaml\Yaml;

class Helper
{
    public const PARAM_LOCALES = 'app.locales';
    public const PARAM_DEFAULT_LOCALE = 'locale';

    private ?KernelInterface $kernel = null;
    /**
     * @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    public function __construct(KernelInterface $kernel, ParameterBagInterface $parameterBag)
    {
        $this->kernel = $kernel;
        $this->parameterBag = $parameterBag;
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

    public function getDefinedLanguages(): array
    {
        if (!$this->parameterBag->has(self::PARAM_LOCALES)) {
            throw new ParameterNotFoundException(
                sprintf('Parameter %s is not found in services.yaml', self::PARAM_LOCALES)
            );
        }
        $languages = explode('|', $this->parameterBag->get(self::PARAM_LOCALES));
        $result = [];
        foreach ($languages as $short) {
            $result[$short] = Languages::getName($short);
        }
        return $result;
    }

    public function getDefaultLanguage(): string
    {
        if (!$this->parameterBag->has(self::PARAM_DEFAULT_LOCALE)) {
            throw new ParameterNotFoundException(
                sprintf('Parameter %s is not found in services.yaml', self::PARAM_DEFAULT_LOCALE)
            );
        }
        return $this->parameterBag->get(self::PARAM_DEFAULT_LOCALE);
    }
}