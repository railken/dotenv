# dotenv

[![Build Status](https://travis-ci.org/railken/dotenv.svg?branch=master)](https://travis-ci.org/railken/dotenv)

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

The class extends the [Dotenv](https://github.com/vlucas/phpdotenv/blob/master/src/Dotenv.php) to add the store method.

If you wish to use indipendently it from the Dotenv, you can use directly the Storage, but you'll need a loader

```php

use Railken\Dotenv\Storage;
use Dotenv\Loader;

$filePath = __DIR__ . "/.env";

$loader = new Loader($filePath);
$loader->load();

$storage = new Storage($loader, $filePath);
$storage->store("APP_KEY", "NEW KEY");
```
