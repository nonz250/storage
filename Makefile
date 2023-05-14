ROOT_PATH := /var/www/app

# Major commands.
.PHONY: setup
setup: docker-build up composer-install storage-link migrate ## Setup project.

.PHONY: reset
reset: clean setup ## Reset container.

.PHONY: up
up: ## Start development server.
	docker compose up -d app web db

.PHONY: down
down: ## Stop development server.
	docker compose down

.PHONY: test
test: lint ## Execute linter and tests.
	docker compose -f docker-compose.yml -f docker-compose.test.yml run --rm app ./vendor/bin/phpunit

.PHONY: pr
pr: lint ## Commands to execute before pull request.
	docker compose -f docker-compose.yml -f docker-compose.test.yml run --rm app ./vendor/bin/phpunit --coverage-text

# Base commands.
.PHONY: docker-build
docker-build: ## Build Docker Containers.
	docker compose build

.PHONY: composer-install
composer-install: ## Install composer packages.
	docker compose run --rm composer install

.PHONY: migrate
migrate: ## Execute migration command.
	docker compose exec app php bin/console migrate

.PHONY: storage-link
storage-link: ## Generate storage symbolic link.
	docker compose exec app ln -fs $(ROOT_PATH)/storage public/storage

.PHONY: clean
clean: ## Clean container.
	docker compose down --volumes
	rm -rf ./backend/vendor

.PHONY: lint
lint: ## Execute linter.
	docker compose -f docker-compose.yml -f docker-compose.test.yml run --rm app php-cs-fixer fix -vv

# Other commands.
.PHONY: help
help: ## Help command.
	@echo "Usage:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-24s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "and other Make task available. Check Makefile."