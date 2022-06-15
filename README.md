# Test Symfony Commands & spatie/crawler

This app is a test of crawling website by command line with Symfony

## Documentation

[Symfony Commands](https://symfony.com/doc/current/console.html)

[spatie/crawler](https://github.com/spatie/crawler)

## Installation

First, just install packages with composer

```bash
composer install
```

## Usage

```bash
# run Symfony dev server
php -S localhost:8000 -t public

# next, you can crawl localwebsite
php bin/console crawl http://localhost:8000
```