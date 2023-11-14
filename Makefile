default: help

help:
	@echo ""
	@echo "Available environment commands:"
	@echo "    start    			bring up the development environment"
	@echo "    stop     			stop the development environment and clear up containers and network"
	@echo ""



# Development Environment Commands
start: _build

stop:
	docker-compose down -v --remove-orphans
	docker network prune -f

_build:
#	docker-compose pull
#	docker-compose build --pull --no-cache

# Change to separate build and than up?
	docker compose up -d --build

# docker compose exec php /bin/bash | symfony check:requirements

# add some commands to run migrations