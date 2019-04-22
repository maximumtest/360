.PHONY: build set-config set-permissions set-xdebug tests unit-tests apply-migrations install-deps bootstrap start stop clear teardown apply-seeds

DOCKER_COMPOSE ?= "docker-compose"

ROOT_PATH := "."
APP_CONTAINER ?= "360-backend"

build:
	$(info [+] Building docker images...)
	@${DOCKER_COMPOSE} build

set-permissions:
	$(info [+] Fixing permissions...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} chmod -R 777 ${ROOT_PATH}/storage ${ROOT_PATH}/bootstrap/cache

set-config:
	$(info [+] Verifying that .env file exists...)
	@([ -f ${ROOT_PATH}/.env ] || (echo [?] Using default .env file... && cp ${ROOT_PATH}/.env.dist ${ROOT_PATH}/.env))

install-deps:
	$(info [+] Installing backend dependencies...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} composer install
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} npm install

set-key: set-config
	$(info [+] Generating application key...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} php artisan key:generate

bootstrap: set-config build start install-deps set-key set-permissions apply-migrations

start:
	$(info [+] Starting dockerized application...)
	@${DOCKER_COMPOSE} up --force-recreate -d

stop:
	$(info [+] Stopping dockerized application...)
	@${DOCKER_COMPOSE} down -v

tests: unit-tests api-tests

unit-tests:
	$(info [+] Running unit tests...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} ./vendor/bin/codecept run unit
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} npm run test:unit

api-tests:
	$(info [+] Running api tests...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} ./vendor/bin/codecept run api

apply-migrations:
	$(info [+] Applying migrations...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} php artisan migrate --force

apply-seeds:
	$(info [+] Applying seeds...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} composer dump-autoload
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} php artisan db:seed

clear: set-permissions
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} composer dump-autoload
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} php artisan optimize

teardown:
	@${DOCKER_COMPOSE} rm -fsv
