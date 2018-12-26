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

        $this->storage = new Storage($this->filePath);
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value)
    {
        $this->storage->store($key, $value, function ($parsedKey, $parsedValue) {
            $this->loader->setEnvironmentVariable($parsedKey, $parsedValue);
        });
    }

    /**
     * @return \Railken\Dotenv\Storage|null
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
