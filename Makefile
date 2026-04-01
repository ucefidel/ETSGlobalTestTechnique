.PHONY: install start stop restart build cache-clear logs bash-backend bash-frontend jwt-keys fixtures composer-install chmod

install: build start composer-install  jwt-keys  chmod
	@echo "Project is ready"

build:
	docker-compose build

start:
	docker-compose up -d

stop:
	docker-compose down

restart:
	docker-compose restart

cache-clear:
	docker-compose exec backend php bin/console cache:clear

jwt-keys:
	docker-compose exec backend php bin/console lexik:jwt:generate-keypair --skip-if-exists

fixtures:
	docker-compose exec backend php bin/console app:fixtures

logs:
	docker-compose logs -f

logs-backend:
	docker-compose logs -f backend

logs-frontend:
	docker-compose logs -f frontend

bash-backend:
	docker-compose exec backend bash

bash-frontend:
	docker-compose exec frontend sh

composer-install:
	docker-compose exec backend composer install --no-interaction

chmod:
	docker-compose exec backend chmod -R 777 .
	sudo chown -R $(USER):$(USER) backend/vendor backend/var

test-backend:
	docker-compose exec backend php bin/phpunit --testdox

test-frontend:
	docker-compose exec frontend npm test