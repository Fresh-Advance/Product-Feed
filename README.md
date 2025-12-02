# Product Feed module for OXID eShop

[![Development](https://github.com/Fresh-Advance/Product-Feed/actions/workflows/trigger.yaml/badge.svg?branch=b-7.3.x)](https://github.com/Fresh-Advance/Product-Feed/actions/workflows/trigger.yaml)
[![Latest Version](https://img.shields.io/packagist/v/fresh-advance/product-feed?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/fresh-advance/product-feed)
[![PHP Version](https://img.shields.io/packagist/php-v/fresh-advance/product-feed)](https://github.com/Fresh-Advance/Product-Feed)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Product-Feed&metric=alert_status)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Product-Feed)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Product-Feed&metric=coverage)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Product-Feed)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Product-Feed&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Product-Feed)

## Features

Produces the feed of products in the JSON format suitable for various marketing channels.

The example structure of the feed:

```json
{
  "products": [
    {
      "id": "12345",
      "brand": "Tea Company",
      "name": "Organic Earl Grey Tea",
      "short_description": "Classic black tea with bergamot aroma.",
      "long_description": "Hand-picked from Sri Lanka’s highlands...",
      "price": "9.99 EUR",
      "weight": "100 g",
      "category": "Black Tea",
      "image_url": "https://yourshop.com/images/earl-grey.jpg",
      "url": "https://yourshop.com/products/earl-grey",
      "availability": "in_stock",
      "enable_search": true,
      "last_updated": "2025-11-09T19:00:00Z"
    }
  ]
}
```

## Limitations

* The current version is producing one feed with all the products, so having large catalog may lead to performance
  issues.
    * Please contact us with a request for more performant solutions if needed, it could be implemented in the future
      versions.

## Branch compatibility

* Branch **b-7.4.x** is compatible with OXID Shop compilation **7.4.0 and up**
* Branch **b-7.3.x** is compatible with OXID Shop compilation **7.3.0 and up**

Note: Not all latest features are available in the older branches.

## Installation

Module is available on packagist and installable via composer

```
composer require fresh-advance/product-feed
./vendor/bin/oe-eshop-doctrine_migration migrations:migrate fa_product_feed
```

# Development installation

To be able running the tests and other preconfigured quality tools, please install the module as
a [root package](https://getcomposer.org/doc/04-schema.md#root-package).

The next section shows how to install the module as a root package by using
the [Fresh Advance Development Base](https://github.com/Fresh-Advance/development).

In case of different environment usage, please adjust by your own needs.

# Development installation on Fresh Advance Development Base

The installation instructions below are shown for the
current [Fresh Advance Development Base](https://github.com/Fresh-Advance/development)
for shop 7.4. Make sure your system meets the requirements of the Development Base.

0. Ensure all docker containers are down to avoid port conflicts

1. Clone the SDK for the new project

```shell
echo MyProject && git clone https://github.com/Fresh-Advance/development.git $_ && cd $_
```

2. Clone the repository to the source directory

```shell
git clone --recurse-submodules https://github.com/Fresh-Advance/Product-Feed.git --branch=b-7.3.x ./source
```

3. Run the recipe to setup the development environment

```shell
./source/recipes/setup-development.sh
```

You should be able to access the shop with http://localhost.local and the admin panel with http://localhost.local/admin
(credentials: noreply@oxid-esales.com / admin)

### Running the tests and quality tools

Check the "scripts" section in the `composer.json` file for the available commands. Those commands can be executed
by connecting to the php container and running the command from there, example:

```shell
make php
composer tests-coverage
```

Commands can be also triggered directly on the container with docker compose, example:

```shell
docker compose exec -T php composer tests-coverage
```

## License

Please make sure you checked the License before using the module. License
subscription can be bought on [MB Arbatos Klubas website](https://arbatosklubas.eu/)
