<?php

use PHPUnit\Framework\TestCase;

use Railken\Dotenv\Dotenv;
use Railken\Dotenv\Storage;

class StorageTest extends TestCase
{
    public function testBasics()
    {
        $this->commonTest([
            'x' => '1',
            'y' => '"2"'
        ], [
            'x' => 'A',
            'y' => 'B'
        ]);
    }

    public function testWithSpecialCharacters()
    {
        $this->commonTest([
            'x' => '1$2',
            'y' => '"My Name"'
        ], [
            'x' => 'A$B',
            'y' => 'My new Name',
        ]);
    }

    public function commonTest($org, $new)
    {
        $path = __DIR__."/../../var";

        $this->assureExistsDir($path);
        file_put_contents($path."/.env", urldecode(http_build_query($org, '', PHP_EOL)));
        $dotenv = new Dotenv($path);
        $dotenv->overload();

        foreach ($new as $key => $value) {
            $dotenv->store($key, $value);
        }

        foreach ($new as $key => $value) {
            $this->assertEquals(getenv($key), $value);
        }

        unlink($path."/.env");
    }

    public function assureExistsDir(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
