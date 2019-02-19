.PHONY: build set-config set-permissions set-xdebug tests unit-tests apply-migrations install-deps bootstrap start stop clear teardown

DOCKER_COMPOSE ?= "docker-compose"

ROOT_PATH := "."
APP_CONTAINER ?= "360-backend"

build:
	$(info [+] Building docker images...)
	@${DOCKER_COMPOSE} build
	$(info [+] Done!)

set-permissions:
	$(info [+] Fixing permissions...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER_BACKEND} chmod -R 777 ${ROOT_PATH}/storage ${ROOT_PATH}/bootstrap/cache

set-config: set-permissions
	$(info [+] Verifying that .env file exists...)
	@([ -f ${ROOT_PATH}/.env ] || (echo [?] Using default .env file... && cp ${ROOT_PATH}/.env.dist ${ROOT_PATH}/.env))

install-deps:
	$(info [+] Installing backend dependencies...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} composer install
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} npm install

set-key: set-config
	$(info [+] Generating application key...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} php artisan key:generate

bootstrap: set-config build start install-deps set-key set-permissions

start:
	$(info [+] Starting dockerized application...)
	@${DOCKER_COMPOSE} up --force-recreate -d
	$(info [+] Done!)

stop:
	$(info [+] Stopping dockerized application...)
	@${DOCKER_COMPOSE} down -v
	$(info [+] Done!)

tests: unit-tests api-tests

unit-tests:
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} ./vendor/bin/codecept run unit
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} npm run tests:unit

api-tests:
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} ./vendor/bin/codecept run api

apply-migrations:
	$(info [+] Applying migrations...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} php artisan migrate --force
	$(info [+] Done!)

clear: set-permissions
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} composer dump-autoload
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} php artisan optimize

teardown:
	@${DOCKER_COMPOSE} rm -fsv
