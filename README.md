# Boilerplate Symfony API

[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://www.php.net/) [![Symfony](https://img.shields.io/badge/Symfony-7.3+-black.svg)](https://symfony.com/) [![Docker](https://img.shields.io/badge/Docker-Compose-blue.svg)](https://docs.docker.com/compose/) [![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## Table of Contents
- [Summary](#summary)
- [Requirements](#requirements)
- [Installation](#installation)
- [Unit Testing](#unit-testing)
- [Acceptance Testing](#acceptance-testing)
- [License](#license)

## Summary

⚠️ CHANGE ME!

Boilerplate created from previous technical assignment. Use it to develop assignments based on PHP & Symfony.

## Requirements

- `Docker` and `Docker Compose` installed on your machine

## Installation

```sh
docker compose up --build
```

As an alternative, if [Make](https://www.gnu.org/software/make/manual/make.html) is installed, you can run:

```sh
make build
```

See available [Makefile targets](Makefile) for more options.

Now, on browser, you can access the REST API at [http://localhost:8000](http://localhost:8000).

## Testing

### REST API execution

Execute the API tests from `docs/api.http`. See the [HTTP Client documentation](https://www.jetbrains.com/help/phpstorm/http-client-in-product-code-editor.html) for more details on how to run these tests.

### Unit Testing

```sh
docker exec -ti backend-api php bin/phpunit
```

### Acceptance Testing

```sh
docker exec -ti backend-api php bin/phpunit --group=acceptance
```

## License

This project is licensed under the MIT License—see the [LICENSE](LICENSE) file for details.
