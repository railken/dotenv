<?php

namespace Railken\Dotenv\Tests\Dotenv;

use Railken\Dotenv\Exceptions\InvalidKeyValuePairException;

class UpdateTest extends TestCase
{
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

    public function testMismatchKey()
    {
        $this->expectException(InvalidKeyValuePairException::class);

        $startingVariables = [
            'x' => '1',
        ];

        $endingVariables = [
            'y' => 'B',
        ];

        $this->iniEnv($startingVariables);
        $this->commonTestStore($endingVariables);

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

    public function commonTestStore($variables)
    {
        $dotenv = $this->getDotenv();

        foreach ($variables as $key => $value) {
            $dotenv->updateVariable($key, $value);
        }
    }
}
