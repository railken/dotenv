<?php

namespace Railken\Dotenv\Tests\Dotenv;

class RemoveTest extends TestCase
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

    public function commonTestRemove($variables)
    {
        $dotenv = $this->getDotenv();

        foreach ($variables as $key => $value) {
            $dotenv->removeVariable($key);
        }
    }
}
