<?php

namespace Railken\Dotenv;

class Variable
{
    /**
     * @var string
     */
    protected $original;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     *
     * @return $this
     */
    public function setKey(string $key): Variable
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function setValue($value): Variable
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $original
     *
     * @return $this
     */
    public function setOriginal(string $original): Variable
    {
        $this->original = $original;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginal()
    {
        return $this->original;
    }

    /**
     * Convert variable into file format.
     *
     * @return string
     */
    public function toFile()
    {
        return $this->getValue() === false ? '' : sprintf('%s=%s', $this->getKey(), $this->getValue());
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKeyFromRaw(string $key): Variable
    {
        return $this->setKey($this->parseKey($key));
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValueFromRaw($value): Variable
    {
        return $this->setValue($this->parseValue($value));
    }

    /**
     * Parse key before putting into the .env file.
     *
     * @param string $key
     *
     * @return string
     */
    public function parseKey(string $key): string
    {
        return $key;
    }

    /**
     * Parse value before putting into the .env file.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function parseValue($value): string
    {
        $value = (string) $value;

        if (preg_match("/\s/", $value)) {
            $value = sprintf('"%s"', $value);
        }

        return $value;
    }
}
