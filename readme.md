# HazzardWeb Docs

[docs.hazzardweb.com](http://docs.hazzardweb.com)

## Installation

- `git clone https://github.com/hazzardweb/docs.hazzardweb.com.git`
- `cp .env.example .env`
- `composer install`
- `php artisan key:generate`
- `npm install`
- `gulp` / `gulp --production`

## Update Docs

- `php artisan docs:update`
- `php artisan docs:update <doc>`
- `php artisan docs:update <doc> <version>`

## Configure Docs

See [docs.yml](docs.yml).
