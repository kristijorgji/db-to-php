# Local development

What to keep in mind when working on this library.

## Table of contents

- [Requirements](#requirements)
- [Docker MySQL](#docker-mysql)
- [Makefile](#makefile)
- [Git hooks](#git-hooks)
- [Test environment file](#test-environment-file)
- [Running tests](#running-tests)
- [Default config and getenv](#default-config-and-getenv)
- [PhpStorm PHPUnit](#phpstorm-phpunit)
- [Host Xdebug](#host-xdebug)
- [Code style](#code-style)

## Requirements

- PHP ^8.3 (8.5 on the host is fine)
- Composer
- Docker (recommended) or a local MySQL for integration tests

```shell
composer install
```

## Docker MySQL

Repo-root [`docker-compose.yml`](../docker-compose.yml) runs official **MySQL 8.4**
with the same credentials as [`tests/.env`](../tests/.env). Host port **23306**
avoids default MySQL (3306) and other common ports.

```shell
make mysql-up
# or: docker compose up -d
# wait until the mysql service is healthy, then:
make test
```

`127.0.0.1:23306`, database `test_db_to_php`, user `root`, password `Test123@`.

GitHub Actions CI is **PHP 8.5** + **MySQL 8.4** on runner port **3306**
and writes its own `tests/.env`. A MySQL already listening on 3306 locally is
fine if you change `DB_PORT`.

MySQL 8 reports `json` columns as JSON (factory `randomJson()`). The committed
integration fixtures match that.

## Makefile

[`Makefile`](../Makefile) mirrors the PHP app targets, trimmed to this library:

```shell
make help
make mysql-up
make lint
make test
make check
make dev-init
```

No Artisan, sqlc, postman, or PHPStan targets.

## Git hooks

Once per clone, after `composer install`:

```shell
make dev-init
make verify-hooks
```

Enabled via [`.kj-php-coding-standard.env`](../.kj-php-coding-standard.env):
markdownlint, related PHPUnit (`src/` → `tests/unit/`), coverage gate.
`KJ_PHP_CS_PHP_RUNTIME=host` — compose is MySQL only.

`git_hooks/` is gitignored; re-run `make dev-init` after package upgrades.

Coverage gate minimum is **99%** (measured clover was 99.16%).
`make test-coverage` writes `build/coverage/clover.xml` (gitignored).

## Test environment file

PHPUnit bootstrap ([`tests/bootstrap.php`](../tests/bootstrap.php)) loads
**`tests/.env`** with `Dotenv::createUnsafeMutable` so `$_ENV` and `getenv()`
both see `DB_*` (needed by [`dbToPhp.cfg.php`](../dbToPhp.cfg.php)). That is
the file PHPUnit reads, not a repo-root `.env`.

Required keys (same as CI and [`MySqlTestCase`](../tests/helpers/MySqlTestCase.php)):

```dotenv
DB_HOST=127.0.0.1
DB_PORT=23306
DB_DATABASE=test_db_to_php
DB_USERNAME=root
DB_PASSWORD=Test123@
```

Root [`.env.dist`](../.env.dist) still uses outdated `MYSQL_DB_*` names. Do not
copy those keys as-is; adapt them to `DB_*` in `tests/.env`.

`createUnsafeMutable` overwrites variables already set in the shell or in a
PhpStorm run configuration. Unset them if you need the file values to win, or
set them after bootstrap if you need to override the file.

## Running tests

```shell
composer tests
# or
vendor/bin/phpunit
```

The unit suite does not need a database. Integration tests need MySQL reachable
at the `DB_*` host (for example `127.0.0.1:23306` via `docker compose up -d`).
`Connection refused` usually means the database is down, not that `.env` failed
to load.

## Default config and getenv

[`dbToPhp.cfg.php`](../dbToPhp.cfg.php) reads `getenv('DB_*')`. PHPUnit bootstrap
uses `createUnsafeMutable`, which calls `putenv`. For CLI generation outside
PHPUnit, export them first or they fall back to the cfg defaults (`3306`):

```shell
set -a && . tests/.env && set +a
vendor/bin/dbToPhp generate:factories
```

## PhpStorm PHPUnit

A working run configuration:

- **Test scope:** Directory → `tests/`
- **Use alternative configuration file:** checked, path = repo-root
  [`phpunit.xml`](../phpunit.xml)

That `phpunit.xml` sets `bootstrap="tests/bootstrap.php"`, which loads
`tests/.env`.

PhpStorm often does **not** pick up `phpunit.xml` automatically when Test scope
is Directory and **Custom working directory** is empty. PHPUnit then uses CWD
`tests/` and looks for `tests/phpunit.xml`, which does not exist, so the root
file (and bootstrap) never run.

Instead of the alternative-file checkbox you can set Custom working directory to
the repo root and use **Defined in the configuration file**.

If the dialog warns that the Xdebug extension is not installed, the **Default
project interpreter** is probably a different PHP than the Homebrew 8.5 CLI that
has Xdebug.

## Host Xdebug

Optional PECL Xdebug on the host CLI, for stepping through this library (not
Docker). Keep the port away from commonly used ports reserved for other
projects. Host setup uses **19003**, `xdebug.mode=debug`,
`xdebug.start_with_request=trigger`.

Point PhpStorm’s debug listener at **19003**. Example:

```shell
XDEBUG_TRIGGER=1 XDEBUG_MODE=debug vendor/bin/phpunit
```

## Code style

```shell
make lint
make fix
# or
composer code-style
composer code-format
```

ECS (`vendor/bin/ecs check` / `--fix`). Markdown via `make lint-markdown` /
`make fix-markdown` (Docker).
