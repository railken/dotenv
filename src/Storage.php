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
     * @param mixed  $value
     */
    public function update(string $key, $value = null)
    {
        $variable = $this->extract($key);

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
        $variable = $this->extract($key);
        $variable->setKeyFromRaw($key);
        $variable->setValue(false);
        $this->persistVariable($variable);

        return $variable;
    }

    /**
     * Get updated file content.
     *
     * @param Variable $variable
     *
     * @return string
     */
    public function getUpdatedFileContent(Variable $variable): string
    {
        if ($variable->getOriginal()) {
            return str_replace($variable->getOriginal(), $variable->toFile(), file_get_contents($this->filePath));
        }

        return file_get_contents($this->filePath).PHP_EOL.$variable->toFile();
    }

    /**
     * Store the variable in the .env file.
     *
     * @param string $key
     */
    protected function extract(string $key)
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
    protected function persistVariable(Variable $variable)
    {
        file_put_contents($this->filePath, $this->getUpdatedFileContent($variable));
    }
}
