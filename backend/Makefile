.PHONY: build set-config set-permissions set-xdebug tests unit-tests apply-migrations install-deps bootstrap start stop clear teardown

DOCKER_COMPOSE ?= "docker-compose"

ROOT_PATH := "."
APP_CONTAINER ?= "r360-backend"

build:
	$(info [+] Building docker images...)
	@${DOCKER_COMPOSE} build
	$(info [+] Done!)

set-permissions:
	$(info [+] Fixing permissions...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} chmod -R 777 ${ROOT_PATH}/storage ${ROOT_PATH}/bootstrap/cache

set-config: set-permissions
	$(info [+] Verifying that .env file exists...)
	@([ -f ${ROOT_PATH}/backend/.env ] || (echo [?] Using default .env file... && cp ${ROOT_PATH}/.env.dist ${ROOT_PATH}/.env))

install-deps:
	$(info [+] Installing dependencies...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} composer install

set-key:
	$(info [+] Generating application key...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER} php artisan key:generate

db-apply-fixture:
	$(info [+] Restoring database from fixture...)
	@${DOCKER_COMPOSE} exec -T lms-v1-postgres psql -U postgres lms < .docker/postgres/fixture.sql

bootstrap: set-config build start install-deps set-key

start:
	$(info [+] Starting dockerized application...)
	@${DOCKER_COMPOSE} up --force-recreate -d
	$(info [+] Done!)

stop:
	$(info [+] Stopping dockerized application...)
	@${DOCKER_COMPOSE} stop
	$(info [+] Done!)

tests: unit-tests api-tests

unit-tests:
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER} ./vendor/bin/codecept run unit

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
