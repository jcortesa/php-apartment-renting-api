# Boilerplate Symfony API

[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://www.php.net/) [![Symfony](https://img.shields.io/badge/Symfony-7.3+-black.svg)](https://symfony.com/) [![Docker](https://img.shields.io/badge/Docker-Compose-blue.svg)](https://docs.docker.com/compose/) [![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## Table of Contents
- [Summary](#summary)
- [Architecture](#architecture)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Testing](#testing)
- [License](#license)

## Summary

This project provides a RESTful API to analyze booking requests for a single apartment rented through multiple platforms. It offers insights such as profit per night and the optimal combination of bookings to maximize profit, following best practices and maintainable code principles.

## Architecture

- Framework: Symfony 7.3+
- Principles: Hexagonal architecture, SOLID, modular and testable code
- Testing: PHPUnit for unit and E2E tests
- Containerization: Docker & Docker Compose

## Requirements

- `Docker` and `Docker Compose` installed on your machine

## Installation

```sh
git clone https://github.com/jcortesa/php-apartment-renting-api
cd php-apartment-renting-api
docker compose up --build
```

As an alternative, if [Make](https://www.gnu.org/software/make/manual/make.html) is installed, you can run:

```sh
make build
```

See available [Makefile targets](Makefile) for more options.

Now, on browser, you can access the REST API at [http://localhost:8000](http://localhost:8000).

## Usage

You can interact with the API using any HTTP client (e.g., cURL, Postman, or the built-in HTTP Client in PhpStorm). Example requests are provided in `docs/api.http`.


## API Endpoints

### `POST /stats`

Given a list of booking requests, returns the average, minimum, and maximum profit per night.

Request Example:

```json
[
  {
    "request_id": "bookata_XY123",
    "check_in": "2020-01-01",
    "nights": 5,
    "selling_rate": 200,
    "margin": 20
  },
  {
    "request_id": "kayete_PP234",
    "check_in": "2020-01-04",
    "nights": 4,
    "selling_rate": 156,
    "margin": 22
  }
]
```

Response Example:

```json
{
    "avg_night": 8.29,
    "min_night": 8,
    "max_night": 8.58
}
```

### `POST /maximize`

Given a list of booking requests, returns the best combination (no overlaps) to maximize total profit.

Request Example:

```json
[
  {
    "request_id": "bookata_XY123",
    "check_in": "2020-01-01",
    "nights": 5,
    "selling_rate": 200,
    "margin": 20
  },
  {
    "request_id": "acme_AAAAA",
    "check_in": "2020-01-10",
    "nights": 4,
    "selling_rate": 160,
    "margin": 30
  }
]
```

Response Example:

```json
{
    "request_ids": [
        "bookata_XY123", 
        "acme_AAAAA"
    ],
    "total_profit": 88,
    "avg_night": 10,
    "min_night": 8,
    "max_night": 12
}
```

For the full API definition, see the [Swagger documentation](https://app.swaggerhub.com/apis-docs/BlackfireSFL/BackendChallenge/1.0.1).

## Testing

### Unit Tests

```sh
docker exec -ti backend-api php bin/phpunit
```

### Acceptance (E2E) Tests

```sh
docker exec -ti backend-api php bin/phpunit --group=acceptance
```

## License

This project is licensed under the MIT License—see the [LICENSE](LICENSE) file for details.
