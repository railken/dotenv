<?php

namespace Railken\Dotenv\Tests\Dotenv;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railken\Dotenv\Dotenv;

class TestCase extends BaseTestCase
{
    public function getPath()
    {
        return __DIR__.'/../../var';
    }

    public function iniEnv($variables)
    {
        $path = $this->getPath();

        $this->assureExistsDir($path);

        file_put_contents($this->getPath().'/.env', urldecode(http_build_query($variables, '', PHP_EOL)));
    }

    public function unlinkEnv()
    {
        unlink($this->getPath().'/.env');
    }

    public function commonTestAssert($variables)
    {
        $dotenv = $this->getDotenv();

        foreach ($variables as $key => $value) {
            $this->assertSame(getenv($key), $value);
        }
    }

    public function getDotenv()
    {
        $dotenv = new Dotenv($this->getPath());
        $dotenv->overload();

        return $dotenv;
    }

    public function assureExistsDir(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
