<?php

namespace Railken\Dotenv\Tests\Dotenv;

class AppendTest extends TestCase
{
    public function testAppend()
    {
        $startingVariables = [
            'x' => '1',
        ];

        $endingVariables = [
            'y' => 'B',
        ];

        $this->iniEnv($startingVariables);
        $this->commonTestStore($endingVariables);
        $this->commonTestAssert(array_merge($startingVariables, $endingVariables));

        $this->unlinkEnv();
    }

    public function commonTestStore($variables)
    {
        $dotenv = $this->getDotenv();

        foreach ($variables as $key => $value) {
            $dotenv->appendVariable($key, $value);
        }
    }
}
