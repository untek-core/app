<?php

namespace Untek\Core\App\Libs;

use Untek\Core\App\Interfaces\EnvironmentInterface;
use Untek\Core\DotEnv\Domain\Libs\Symfony\SymfonyBootstrap;
use Untek\Core\DotEnv\Domain\Libs\Vlucas\VlucasBootstrap;

class SymfonyEnvironment extends BaseEnvironment implements EnvironmentInterface
{

    protected ?string $content = null;
    protected ?array $env = null;
    protected ?string $path = null;

    public function __construct(SymfonyBootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    public function init(string $mode = null, string $rootDirectory = null): void
    {
        $basePath = $this->path ?: $rootDirectory;
        $this->bootstrap->setMode($mode);
        $this->bootstrap->setRootDirectory($rootDirectory);

        if ($this->content) {
            $this->bootstrap->loadFromContent($this->content);
//        $this->bootstrap->loadFromContent($this->getDefaultEnvContent());
        } elseif ($this->env) {
            $this->bootstrap->loadFromArray($this->env);
        } else {
            $this->bootstrap->loadFromPath($basePath, $this->getFileNames());
        }
    }

    protected function getDefaultEnvContent(): string
    {
        $mode = $this->bootstrap->getMode();
        $rootDirectory = $this->bootstrap->getRootDirectory();
        $files = $this->getFileNames();
        $content = '';
        foreach ($files as $file) {
            $content .= file_get_contents($rootDirectory . '/' . $file) . PHP_EOL;
        }
        return $content;
    }
}
