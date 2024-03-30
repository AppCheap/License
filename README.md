# Appcheap Client Library for Licensing and Update Wordpress Plugin #

## Requirements ##
* [PHP 7.4 or higher](https://www.php.net/)

## Installation ##

You can use **Composer** or simply **Download the Release**

### Composer

The preferred method is via [composer](https://getcomposer.org/). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require appcheap/license:^1.0
```

Finally, autoloader library in your main plugin file like this:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

## Usage

### Initialize the client

```php
$client = new Appcheap\Client('PRODUCT_IDENTIFY', 'API_URL');
```
### Plugin update

```php
$plugin = new Appcheap\Plugin($client, 'PLUGIN_BASE_NAME');
$plugin->run();
```