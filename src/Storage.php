<?php

namespace Railken\Dotenv;

use Railken\Dotenv\Exceptions\InvalidKeyValuePairException;

class Storage
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $filePath;

    /**
     * Create a new storage instance.
     *
     * @param string $dirPath
     * @param string $env
     */
    public function __construct($dirPath = __DIR__, $env = '.env')
    {
        $this->filePath = $dirPath.'/'.$env;
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     */
    public function prepare(string $key)
    {
        $content = file_get_contents($this->filePath);
        $oldValue = getenv($key);

        $oldValue = str_replace('$', '\$', $oldValue);

        if (preg_match(sprintf('/%s=%s/', $key, $oldValue), $content, $matches)) {
            $variable = $this->createVariable($matches);
        }

        if (preg_match(sprintf('/%s="%s"/', $key, $oldValue), $content, $matches)) {
            $variable = $this->createVariable($matches);
        }

        if (!isset($variable)) {
            throw new InvalidKeyValuePairException(sprintf('%s=%s', $key, $oldValue));
        }

        return $variable;
    }

    /**
     * Set the new value.
     *
     * @param array $matches
     */
    public function createVariable(array $matches)
    {
        $variable = new Variable();
        $variable->setOriginal($matches[0]);

        return $variable;
    }

    /**
     * Replace string in .env file.
     *
     * @param Variable $variable
     */
    public function persistVariable(Variable $variable)
    {
        file_put_contents($this->filePath, str_replace($variable->getOriginal(), $variable->toFile(), file_get_contents($this->filePath)));
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value)
    {
        $variable = $this->prepare($key);
        $variable->setKeyFromRaw($key);
        $variable->setValueFromRaw($value);
        $this->persistVariable($variable);

        return $variable;
    }

    /**
     * Remove the variable in the .env file.
     *
     * @param string $key
     */
    public function remove(string $key)
    {
        $variable = $this->prepare($key);
        $variable->setKeyFromRaw($key);
        $variable->setValue(false);
        $this->persistVariable($variable);

        return $variable;
    }
}
