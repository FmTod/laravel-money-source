<?php

declare(strict_types=1);

namespace FmTod\Money;

use Exception;
use Illuminate\Config\Repository;

/**
 * Class Config
 *
 * @author Serhii Andriichuk <andriichuk29@gmail.com>
 */
final class Config
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @throws Exception
     * @return void
     */
    public function __construct()
    {
        $this->config = new Repository(require $this->configFile());
    }

    /**
     * @return string
     * @throws Exception
     */
    private function configFile(): string
    {
        $appConfigFile = $this->appConfigFilePath();

        if ($appConfigFile && file_exists($appConfigFile)) {
            return $appConfigFile;
        }

        $packageConfigFile = $this->packageConfigFilePath();

        if (file_exists($packageConfigFile)) {
            return $packageConfigFile;
        }

        throw new Exception('Configuration file for Money package not found');
    }

    private function appConfigFilePath(): string
    {
        if (function_exists('config_path')) {
            return config_path($this->fileName());
        }

        return '';
    }

    private function packageConfigFilePath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . $this->fileName();
    }

    private function fileName(): string
    {
        return 'money.php';
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->config->get($key);
    }
}
