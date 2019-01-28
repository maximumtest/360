.PHONY: build set-config set-permissions set-xdebug tests unit-tests apply-migrations install-deps-backend bootstrap start stop clear teardown

DOCKER_COMPOSE ?= "docker-compose"

ROOT_PATH := "."
APP_CONTAINER_BACKEND ?= "r360-backend"
APP_CONTAINER_FRONTEND ?= "r360-frontend"

build-frontend:
	$(info [+] Building frontend bundles)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER_FRONTEND} npm run build

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

install-deps-backend:
	$(info [+] Installing backend dependencies...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER_BACKEND} composer install

set-key: set-config
	$(info [+] Generating application key...)
	@${DOCKER_COMPOSE} run ${APP_CONTAINER_BACKEND} php artisan key:generate

bootstrap: set-config build start install-deps-backend set-key set-permissions

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
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER_BACKEND} ./vendor/bin/codecept run unit

api-tests:
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER_BACKEND} ./vendor/bin/codecept run api

apply-migrations:
	$(info [+] Applying migrations...)
	@${DOCKER_COMPOSE} exec -T ${APP_CONTAINER_BACKEND} php artisan migrate --force
	$(info [+] Done!)

clear: set-permissions
	@${DOCKER_COMPOSE} run ${APP_CONTAINER_BACKEND} composer dump-autoload
	@${DOCKER_COMPOSE} run ${APP_CONTAINER_BACKEND} php artisan optimize

teardown:
	@${DOCKER_COMPOSE} rm -fsv
