<?php

namespace Untek\Core\App\Libs;

use Symfony\Component\DependencyInjection\Loader\FileLoader;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class ConfigFinder
{

    public function __construct(
        private string $pathTemplate,
        private string $nameTemplate,
    )
    {
    }

    public function load(array $paths, string $rootDirectory, FileLoader $loader): void
    {
        $list = $this->find($paths, $rootDirectory);
        foreach ($list as $item) {
            $loader->load($rootDirectory . '/' . $item);
        }
    }

    public function find(array $paths, string $rootDirectory): array
    {
        $list = [];
        foreach ($paths as $path) {
            $files = (new Finder())
                ->files()
                ->path($this->pathTemplate)
                ->name($this->nameTemplate)
                ->in($path);
            foreach ($files as $file) {
                /** @var SplFileInfo $file */
                $list[] = Path::makeRelative($file->getRealPath(), $rootDirectory);
            }
        }
        return $list;
    }
}
