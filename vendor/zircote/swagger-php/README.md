[![Build Status](https://img.shields.io/github/workflow/status/zircote/swagger-php/build?style=flat-square)](https://github.com/zircote/swagger-php/actions?query=workflow:build)
[![Total Downloads](https://img.shields.io/packagist/dt/zircote/swagger-php.svg?style=flat-square)](https://packagist.org/packages/zircote/swagger-php)
[![License](https://img.shields.io/badge/license-Apache2.0-blue.svg?style=flat-square)](LICENSE)

# swagger-php

Generate interactive [OpenAPI](https://www.openapis.org) documentation for your RESTful API using [doctrine annotations](https://www.doctrine-project.org/projects/doctrine-annotations/en/latest/index.html).

For a full list of supported annotations, please have look at the [`OpenApi\Annotations` namespace](src/Annotations) or the [documentation website](https://zircote.github.io/swagger-php/guide/annotations.html).

## Features

- Compatible with the OpenAPI **3.0** and **3.1** specification.
- Extracts information from code & existing phpdoc annotations.
- Command-line interface available.
- [Documentation site](https://zircote.github.io/swagger-php/) with a getting started guide.
- Exceptional error reporting (with hints, context)
- As of PHP 8.1 all annotations are also available as PHP attributes

## OpenAPI version support

`swagger-php` allows to generate specs either for **OpenAPI 3.0.0** or **OpenAPI 3.1.0**.
By default the spec will be in version `3.0.0`. The command line option `--version` may be used to change this
to `3.1.0`.

Programmatically, the method `Generator::setVersion()` can be used to change the version.

## Requirements

`swagger-php` requires at least PHP 7.2 for annotations and PHP 8.1 for using attributes.

## Installation (with [Composer](https://getcomposer.org))

```bash
composer require zircote/swagger-php
```

For cli usage from anywhere install swagger-php globally and make sure to place the `~/.composer/vendor/bin` directory in your PATH so the `openapi` executable can be located by your system.

```bash
composer global require zircote/swagger-php
```

## Usage

Add annotations to your php files.

```php
/**
 * @OA\Info(title="My First API", version="0.1")
 */

/**
 * @OA\Get(
 *     path="/api/resource.json",
 *     @OA\Response(response="200", description="An example resource")
 * )
 */
```

Visit the [Documentation website](https://zircote.github.io/swagger-php/) for the [Getting started guide](https://zircote.github.io/swagger-php/Getting-started.html) or look at the [Examples directory](Examples/) for more examples.

### Usage from php

Generate always-up-to-date documentation.

```php
<?php
require "vendor/autoload.php";
$openapi = \OpenApi\Generator::scan(["/path/to/project"]);
header("Content-Type: application/x-yaml");
echo $openapi->toYaml();
```

Documentation of how to use the `Generator` class can be found in the [Generator reference](https://zircote.github.io/swagger-php/reference/generator).

### Usage from the Command Line Interface

The `openapi` command line interface can be used to generate the documentation to a static yaml/json file.

```bash
./vendor/bin/openapi --help
```

Starting with version 4 the default analyser used on the command line is the new `ReflectionAnalyser`.

Using the `--legacy` flag (`-l`) the legacy `TokenAnalyser` can still be used.

### Usage from the Deserializer

Generate the OpenApi annotation object from a json string, which makes it easier to manipulate objects programmatically.

```php
<?php

use OpenApi\Serializer;

$serializer = new Serializer();
$openapi = $serializer->deserialize($jsonString, "OpenApi\Annotations\OpenApi");
echo $openapi->toJson();
```

### Usage from [docker](https://docker.com)

Generate the swagger documentation to a static json file.

```
docker run -v "$PWD":/app -it tico/swagger-php --help
```

## More on OpenApi & Swagger

- https://swagger.io
- https://www.openapis.org
- [OpenApi Documentation](https://swagger.io/docs/)
- [OpenApi Specification](http://swagger.io/specification/)
- [Related projects](docs/related-projects.md)

## Contributing

Feel free to submit [Github Issues](https://github.com/zircote/swagger-php/issues)
or pull requests.

The documentation website is build from the [docs](docs/) folder with [vitepress](https://vitepress.vuejs.org).

Make sure pull requests pass [PHPUnit](https://phpunit.de/)
and [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) (PSR-2) tests.

### To run both unit tests and linting execute:

```bash
composer test
```

### Running unit tests only:

```bash
./bin/phpunit
```

### Regenerate annotation/attribute reference markup docs

```bash
composer docs:refgen
```

### Running linting only:

```bash
composer lint
```

### To make `php-cs-fixer` fix linting errors:

```bash
composer cs
```
