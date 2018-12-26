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

        $variable = new Variable();

        if (preg_match(sprintf('/%s=%s/', $key, $oldValue), $content, $matches)) {
            $variable->setOriginal($matches[0]);
        }

        if (preg_match(sprintf('/%s="%s"/', $key, $oldValue), $content, $matches)) {
            $variable->setOriginal($matches[0]);
        }

        if (!$variable->getOriginal()) {
            throw new InvalidKeyValuePairException(sprintf('%s=%s', $key, $oldValue));
        }

        return $variable;
    }

    /**
     * Replace string in .env file.
     *
     * @param Variable $variable
     */
    public function persistVariable(Variable $variable)
    {
        if ($variable->getOriginal()) {
            file_put_contents($this->filePath, str_replace($variable->getOriginal(), $variable->toFile(), file_get_contents($this->filePath)));
        } else {
            file_put_contents($this->filePath, file_get_contents($this->filePath).PHP_EOL.$variable->toFile());
        }
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function store(string $key, $value = null)
    {
        $variable = $this->prepare($key);

        $variable->setKeyFromRaw($key);
        $variable->setValueFromRaw($value);
        $this->persistVariable($variable);

        return $variable;
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function append(string $key, $value = null)
    {
        $variable = new Variable();
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
