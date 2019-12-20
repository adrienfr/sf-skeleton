.DEFAULT_GOAL := help

CONSOLE= php bin/console
CONSOLE_TEST=$(PHP_TEST) php bin/console --env=test

# tests
.PHONY: test test-init test-lint test-phpunit

test: test-init test-lint test-phpunit test-behat ## Launch tests

test-init: vendor ## Initialize tests
	$(CONSOLE_TEST) doctrine:database:drop --if-exists --force
	$(CONSOLE_TEST) doctrine:database:create
	$(CONSOLE_TEST) doctrine:schema:update --force

test-lint: vendor ## Launch Yaml lint tests
	$(CONSOLE_TEST) lint:yaml --parse-tags config
	$(CONSOLE_TEST) lint:yaml --parse-tags src
	$(CONSOLE_TEST) lint:twig templates

test-phpunit: vendor ### Launch PHPUnit tests
	$(CONSOLE_TEST) doctrine:fixtures:load --no-interaction
	php bin/phpunit

test-phpunit-wip: vendor ### Launch PHPUnit tests with WIP tag
	$(CONSOLE_TEST) doctrine:fixtures:load --no-interaction
	php bin/phpunit --group wip

# fix
.PHONY: fix-php-cs-fixer

fix-php-cs-fixer: ## Fix PHP coding style
	php bin/php-cs-fixer fix

# rules from files
vendor: composer.lock
	composer install

composer.lock: composer.json
	@echo composer.lock is not up to date.
