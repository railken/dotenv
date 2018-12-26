<?php

use PHPUnit\Framework\TestCase;
use Railken\Dotenv\Dotenv;

class StorageTest extends TestCase
{

    public function testRemove()
    {
    	$startingVariables = [
            'x' => '1',
            'y' => '"2"',
        ];

        $endingVariables = [
            'x' => '1',
        ];

        $this->iniEnv($startingVariables);
        $this->commonTestRemove($endingVariables);
        $this->commonTestAssert(['y' => '2', 'x' => false]);

        $this->unlinkEnv();
    }

    public function testBasics()
    {
    	$startingVariables = [
            'x' => '1',
            'y' => '"2"',
        ];

        $endingVariables = [
            'x' => 'A',
            'y' => 'B',
        ];

        $this->iniEnv($startingVariables);
        $this->commonTestStore($endingVariables);
        $this->commonTestAssert($endingVariables);

        $this->unlinkEnv();
    }

    public function testWithSpecialCharacters()
    {
        $startingVariables = [
            'x' => '1$2',
            'y' => '"My Name"',
        ];

        $endingVariables = [
            'x' => 'A$B',
            'y' => 'My new Name',
        ];

        $this->iniEnv($startingVariables);
        $this->commonTestStore($endingVariables);
        $this->commonTestAssert($endingVariables);

        $this->unlinkEnv();
    }

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

    public function commonTestStore($variables)
    {
        $dotenv = $this->getDotenv();

        foreach ($variables as $key => $value) {
            $dotenv->storeVariable($key, $value);
        }
    }

    public function commonTestRemove($variables)
    {
        $dotenv = $this->getDotenv();

        foreach ($variables as $key => $value) {
            $dotenv->removeVariable($key);
        }
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
