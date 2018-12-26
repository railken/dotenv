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
     *
     * @return void
     */
    public function __construct($path, $file = '.env')
    {
        parent::__construct($path, $file);

        $this->storage = new Storage($this->loader, $this->filePath);
    }

    /**
     * Store the variable in the .env file
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function store(string $key, $value)
    {
        $this->storage->store($key, $value);
    }
    
    /**
     * @return \Railken\Dotenv\Storage|null
     */
    public function getStorage()
    {
        return $this->storage;
    }
}