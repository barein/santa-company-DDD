USERID=$(shell id -u)
GROUPID=$(shell id -g)
USERNAME=$(shell whoami)

CONSOLE=php bin/console
DOCKER=docker-compose --env-file ./docker/.env
DOCKER-EXEC=$(DOCKER) exec
EXECROOT=$(DOCKER-EXEC) -u 0:0 php
EXEC=$(DOCKER-EXEC) php


### DOCKER UTILITIES ####

## build containers creating current user in php container
build:
	USER_ID=$(USERID) GROUP_ID=$(GROUPID) USERNAME=$(USERNAME) $(DOCKER) build

install: build composer db-restore-with-fixture cc npm-install npm-build

start:
	$(DOCKER) up -d --remove-orphans

stop:
	$(DOCKER) down

ps:
	$(DOCKER) ps

logs:
	$(DOCKER) logs -f

restart: stop start

bash_root:
	$(EXECROOT) bash

bash:
	$(EXEC) bash

######## PROJECT UTILITIES ########

composer-install:
	$(EXEC) composer install --prefer-dist --no-progress --no-interaction

composer: composer-install cc

## clear Symfony cache
cc:
	$(EXECROOT) rm -rf ./var/cache/*
	$(EXEC) composer dump-autoload
	$(EXEC) $(CONSOLE) cache:clear

consume:
	$(EXEC) $(CONSOLE) messenger:consume letter_processing_queue global_queue -vv

######## DATABASE ########

db-drop:
	$(EXEC) $(CONSOLE) doctrine:database:drop --force --if-exists

db-create:
	$(EXEC) $(CONSOLE) doctrine:database:create --if-not-exists

migrations-apply:
	$(EXEC) $(CONSOLE) do:mi:mi -n

db-schema-create:
	$(EXEC) $(CONSOLE) doctrine:schema:create

db-restore: db-drop db-create db-schema-create

db-restore-test:
	$(EXEC) $(CONSOLE) doctrine:database:drop --env=test --force --if-exists
	$(EXEC) $(CONSOLE) doctrine:database:create --env=test --if-not-exists
	$(EXEC) $(CONSOLE) doctrine:schema:create --env=test

fixture:
	$(EXEC) $(CONSOLE) doctrine:fixtures:load --no-interaction

fixture-test:
	$(EXEC) $(CONSOLE) doctrine:fixtures:load --env=test --no-interaction

db-restore-with-fixture: db-restore fixture
db-restore-test-with-fixture: db-restore-test fixture-test

######## JAVASCRIPT ########

npm-install:
	$(EXEC) npm install --force

npm-watch:
	$(EXEC) npm run watch

npm-build:
	$(EXEC) npm run build

######## CONTINUOUS INTEGRATION ########

## Dump php-cs-fixer errors
cs-dump:
	$(EXEC) composer run cs-dump

## Fix php-cs-fixer errors
cs:
	$(EXEC) vendor/bin/php-cs-fixer fix -v

stan:
	$(EXEC) composer run stan

deptrac:
	$(EXEC) composer run deptrac

soft-dependencies:
	$(EXEC) composer run soft-dependencies

validate-composer:
	$(EXEC) composer run validate-composer

unit-tests:
	$(EXEC) composer run unit-tests

functional-tests:
	$(EXEC) composer run functional-tests

tests: unit-tests functional-tests

ci:
	$(EXEC) composer run ci
