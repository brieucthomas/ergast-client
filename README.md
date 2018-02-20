# Ergast Client

[![Build Status](https://img.shields.io/travis/brieucthomas/ergast-client/master.svg?style=flat-square)](https://travis-ci.org/brieucthomas/ergast-client)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Version](https://img.shields.io/github/release/brieucthomas/ergast-client.svg?style=flat-square)](https://github.com/brieucthomas/ergast-client/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/brieucthomas/ergast-client.svg?style=flat-square)](https://packagist.org/packages/brieucthomas/ergast-client)

PHP client for [Ergast Developer API](http://ergast.com/mrd/).

## Requirements

PHP needs to be a minimum version of PHP 7.

## Installation

This library can be easily installed via [Composer](https://getcomposer.org/):

```bash
composer require brieucthomas/ergast-client
```

or just add it to your ```composer.json``` file directly and run composer install.

## Usage

```php
use BrieucThomas\ErgastClient\ErgastClientBuilder;
use BrieucThomas\ErgastClient\Request\RequestBuilder;

$ergastClient = ErgastClientBuilder::createErgastClient();

$requestBuilder = new RequestBuilder();
$requestBuilder
    ->findCircuits()
    ->byId('monza')
;

$response = $ergastClient->execute($requestBuilder->build());
$circuit = $response->getCircuits()->first();

echo $circuit->getId();                       // "monza"
echo $circuit->getName();                     // "Autodromo Nazionale di Monza"
echo $circuit->getUrl();                      // "http://en.wikipedia.org/wiki/Autodromo_Nazionale_Monza"
echo $circuit->getLocation()->getLocality();  // "Monza"
echo $circuit->getLocation()->getCountry();   // "Italy"
echo $circuit->getLocation()->getLatitude();  // 45.6156
echo $circuit->getLocation()->getLongitude(); // 9.28111
```

## Limit and offset

```php
$uriBuilder
    ->setFirstResult(2)
    ->setMaxResults(5)
;
``` 