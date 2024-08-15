<?php

namespace Untek\Core\App\Bootstrap;

use Untek\Core\DotEnv\Domain\Libs\Vlucas\VlucasBootstrap;

DeprecateHelper::hardThrow();

class DotEnvLoader
{

    private string $projectDirectory;
    private string $mode;
    private string $context;
    private string $envDirectory;

    public function __construct(string $projectProject, string $context, bool $isTest = false, ?string $envDirectory = null)
    {
        $this->projectDirectory = $projectProject;
        $this->mode = $isTest ? 'test' : 'main';
        $this->context = $context;
        if(empty($envDirectory)) {
            $envDirectory = $projectProject;
        }
        $this->envDirectory = $envDirectory;
    }

    public function load(string $path = null): void
    {
        $names = $this->getFileNames($this->mode);
        $environmentBootstrap = new VlucasBootstrap($this->mode, $this->projectDirectory);
        $environmentBootstrap->loadFromArray(
            [
                'APP_CONTEXT' => $this->context,
                'APP_MODE' => $this->mode,
            ]
        );
        $environmentBootstrap->loadFromPath($path ?: $this->envDirectory, $names);
    }

    private function getFileNames(string $mode): array
    {
        return [
            '.env',
            $mode == 'test' ? '.env.test' : '.env.local',
        ];
    }
}
