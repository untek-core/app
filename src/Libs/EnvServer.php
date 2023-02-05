<?php

namespace Untek\Core\App\Libs;

class EnvServer
{

    private $server;
    
    public function __construct(array $server)
    {
        $this->server = $server;
    }

    public function isEqualUri(string $name)
    {
        return trim($this->server['REQUEST_URI'], '/') == trim($name, '/');
    }

    public function isPostMethod(): bool
    {
        return $this->server['REQUEST_METHOD'] == 'POST';
    }

    public function isOptionsMethod(): bool
    {
        return $this->server['REQUEST_METHOD'] == 'OPTIONS';
    }

    public function isContainsSegmentUri(string $name)
    {
        $isMatch = preg_match('/(\/' . $name . ')($|\/|\?)/', $this->server['REQUEST_URI'], $matches);
        return $isMatch ? $matches[1] : null;
    }

    public function fixUri(string $name)
    {
        if (ltrim($this->server['REQUEST_URI'], '/') === $name) {
            $this->server['REQUEST_URI'] .= '/';
        }
        $this->server['SCRIPT_NAME'] = "/$name/index.php";
        $this->server['PHP_SELF'] = "/$name/index.php";
    }
}
