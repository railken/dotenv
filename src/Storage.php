<?php
namespace Railken\Dotenv;

use Dotenv\Loader;
use Railken\Dotenv\Exceptions\InvalidKeyValuePairException;
use Closure;

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
     * @param string $filePath
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Store the variable in the .env file
     *
     * @param string $key
     * @param mixed $value
     * @param Closure $callback
     *
     * @return void
     */
    public function store(string $key, $value = null, Closure $callback = null)
    {
        $content = file_get_contents($this->filePath);
        $oldValue = getenv($key);

        $oldValue = str_replace("$", "\\$", $oldValue);

        if (preg_match(sprintf("/%s=%s/", $key, $oldValue), $content, $matches)) {
            return $this->set($matches, $key, $value, $callback);
        }

        if (preg_match(sprintf("/%s=\"%s\"/", $key, $oldValue), $content, $matches)) {
            return $this->set($matches, $key, $value, $callback);
        }

        throw new InvalidKeyValuePairException(sprintf("%s=%s", $key, $oldValue));
    }

    /**
     * Parse key before putting into the .env file
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
     * Parse value before putting into the .env file
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

    /** 
     * Set the new value
     *
     * @param array $matches
     * @param string $key
     * @param mixed $value
     * @param Closure $callback
     */
    public function set(array $matches, string $key, $value, Closure $callback = null)
    {
        $key = $this->parseKey($key);
        $value = $this->parseValue($value);

        if ($callback) {
            $callback($key, $value);
        }

        return $this->replace($matches[0], sprintf("%s=%s", $key, $value));
    }

    /**
     * Replace string in .env file
     *
     * @param string $original
     * @param string $new
     *
     * @return void
     */
    public function replace(string $original, string $new)
    {
        file_put_contents($this->filePath, str_replace($original, $new, file_get_contents($this->filePath)));
    }
}
