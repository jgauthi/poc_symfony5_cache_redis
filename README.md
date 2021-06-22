# POC Symfony 5: Cache system (redis)
## Prerequisites

* The PHP version must be greater than or equal to PHP 8.0
* The SQLite 3 extension must be enabled
* The JSON extension must be enabled
* The Ctype extension must be enabled
* The date.timezone parameter must be defined in php.ini

More information on [symfony website](https://symfony.com/doc/5.2/reference/requirements.html).

## Features developed
Usage [cache system](https://symfony.com/doc/current/cache.html#cache-configuration-with-frameworkbundle) (redis).

## Installation
Command lines:

```bash
# clone current repot
composer install

# (optional) Copy and edit configuration values ".env.local"
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n

# Optional
php bin/console doctrine:fixtures:load -n
```

For the asset symlink install, launch a terminal on administrator in windows environment.

## Usage
Just execute this command to run the built-in web server _(require [symfony installer](https://symfony.com/download))_ and access the application in your browser at <http://localhost:8081>:

```bash
docker-composer up -d
```

Alternatively, you can [configure a web server](https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html) like Nginx or Apache to run the application.

Your commit is checked by several dev tools (like phpstan, php cs fixer...). These tools were managed by [Grumphp](https://github.com/phpro/grumphp), you can edit configuration on file [grumphp.yml](./grumphp.yml) or check manually with the command: `./vendor/bin/grumphp run`.