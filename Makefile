.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Installing project
	composer install
	make reset
	yarn
	yarn encore dev

reset: ## Resetting database
	bin/console doctrine:database:drop --if-exists --force
	bin/console doctrine:database:create
	bin/console doctrine:migrations:migrate --no-interaction

test: ## Run all tests
	bin/phpspec run -fpretty
	bin/phpstan
	bin/ecs

e2e: ## Run all e2e tests
	yarn run cypress open

watch:
	yarn encore dev --watch