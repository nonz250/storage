.PHONY: setup
setup:
	@docker compose build
	@docker compose run --rm composer install

.PHONY: up
up:
	@docker compose up -d app web db

.PHONY: down
down:
	@docker compose down

.PHONY: test
test:
	@make fix
	@docker compose exec app ./vendor/bin/phpunit

.PHONY: prod
prod:
	@docker compose exec app php -version
	@docker compose exec app php-cs-fixer fix -vv
	@docker compose exec app ./vendor/bin/phpunit --coverage-text

.PHONY: fix
fix:
	@docker compose exec app php -version
	@docker compose exec app php-cs-fixer fix
