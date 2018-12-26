<?php

namespace Railken\Dotenv;

class Variable
{
    protected $original;
    protected $key;
    protected $value;

    public function setKey(string $key)
    {
        $this->key = $key;

        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setOriginal($original)
    {
        $this->original = $original;

        return $this;
    }

    public function getOriginal()
    {
        return $this->original;
    }

    public function toFile()
    {
        return $this->getValue() === false ? '' : sprintf('%s=%s', $this->getKey(), $this->getValue());
    }
    public function setKeyFromRaw(string $key): Variable
    {
        return $this->setKey($this->parseKey($key));
    }

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
