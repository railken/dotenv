<?php

namespace Railken\Dotenv;

use Dotenv\Dotenv as BaseDotenv;

class Dotenv extends BaseDotenv
{
    /**
     * The storage instance.
     *
     * @var \Railken\Dotenv\Storage|null
     */
    protected $storage;

    /**
     * Create a new dotenv instance.
     *
     * @param string $path
     * @param string $file
     */
    public function __construct($path, $file = '.env')
    {
        parent::__construct($path, $file);

        $this->storage = new Storage($path, $file);
    }

    /**
     * @return \Railken\Dotenv\Storage|null
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function storeVariable(string $key, $value)
    {
        $variable = $this->storage->store($key, $value);

        $this->loader->setEnvironmentVariable($variable->getKey(), $variable->getValue());
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     */
    public function removeVariable(string $key)
    {
        $variable = $this->storage->remove($key);

        $this->loader->clearEnvironmentVariable($variable->getKey());
    }
}
