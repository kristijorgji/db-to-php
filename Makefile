#!make

include vendor/kristijorgji/php-coding-standard/make/markdown.mk

.PHONY: help dev-init verify-hooks mysql-up mysql-down \
	test test-coverage lint lint-markdown fix fix-markdown check code-analyse code-modernize

help:
	@echo
	@echo "Hooks"
	@echo "--------------------------------------------------------------------------------"
	@echo "  dev-init             Register git hooks (core.hooksPath)"
	@echo "  verify-hooks         Verify git hooks (core.hooksPath)"
	@echo
	@echo "MySQL"
	@echo "--------------------------------------------------------------------------------"
	@echo "  mysql-up             docker compose up -d (MySQL 8.4 on host 23306)"
	@echo "  mysql-down           docker compose down"
	@echo
	@echo "Quality"
	@echo "--------------------------------------------------------------------------------"
	@echo "  lint                 composer code-style + code-analyse + lint-markdown"
	@echo "  lint-markdown        markdownlint-cli2 (Docker, read-only)"
	@echo "  check                composer check (audit, yaml-lint, format, style, phpstan, tests)"
	@echo "  code-analyse         composer code-analyse (PHPStan)"
	@echo "  code-modernize       composer code-modernize (Rector, writes)"
	@echo "  fix                  composer code-format + fix-markdown"
	@echo "  fix-markdown         Prettier + markdownlint --fix (Docker)"
	@echo "  test                 composer tests (PHPUnit)"
	@echo "  test-coverage        composer tests-coverage (clover + threshold)"
	@echo

# -------------------------------------------------------------------------------------------------
# Hooks
# -------------------------------------------------------------------------------------------------

dev-init:
	@vendor/bin/kj-php-coding-standard-install-hooks

verify-hooks:
	@bash vendor/kristijorgji/php-coding-standard/scripts/check-hooks.sh

# -------------------------------------------------------------------------------------------------
# MySQL
# -------------------------------------------------------------------------------------------------

mysql-up:
	docker compose up -d

mysql-down:
	docker compose down

# -------------------------------------------------------------------------------------------------
# Quality
# -------------------------------------------------------------------------------------------------

test:
	XDEBUG_MODE=off composer tests

test-coverage:
	composer tests-coverage

check:
	XDEBUG_MODE=off composer check

lint:
	XDEBUG_MODE=off composer code-style
	XDEBUG_MODE=off composer code-analyse
	@$(MAKE) --no-print-directory lint-markdown

code-analyse:
	XDEBUG_MODE=off composer code-analyse

code-modernize:
	XDEBUG_MODE=off composer code-modernize

# -------------------------------------------------------------------------------------------------
# Writing / auto-fix
# -------------------------------------------------------------------------------------------------

fix:
	XDEBUG_MODE=off composer code-format
	@$(MAKE) --no-print-directory fix-markdown
