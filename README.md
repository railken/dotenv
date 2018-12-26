# dotenv

[![Build Status](https://travis-ci.org/railken/dotenv.svg?branch=master)](https://travis-ci.org/railken/dotenv)

This library is an extension of [Dotenv](https://github.com/vlucas/phpdotenv) that grant you the ability to persist updated variables into the `.env` file.
# Requirements

PHP 7.1 and later.

## Installation

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/dotenv
```

## Usage

A simple usage looks like: 

```php
use Railken\Dotenv\Dotenv;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$dotenv->store("APP_KEY", "NEW KEY");
```

The class `Railken\Dotenv\Dotenv` simply extends the class `Dotenv\Dotenv` as you can see [here](https://github.com/railken/dotenv/blob/master/src/Dotenv.php#L6)

If you wish you can use directly the `Railken\Dotenv\Storage`

```php
use Railken\Dotenv\Storage;

$filePath = __DIR__ . "/.env";

$storage = new Storage($filePath);
$storage->store("APP_KEY", "NEW KEY");
```
