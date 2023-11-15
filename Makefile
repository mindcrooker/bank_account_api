default: help

help:
	@echo ""
	@echo "Available environment commands:"
	@echo "    start    			pull images, rebuild and bring up the development environment"
	@echo "    stop     			stop the development environment and clear up containers and network"
	@echo "	   fast 				bring up the without forcing to pull images"



# Development Environment Commands
start: _build 

_build:
	docker-compose build --pull --no-cache
	docker compose up -d

stop:
	docker-compose down -v --remove-orphans
	docker network prune -f

fast:
	docker compose up-d --build

# Run migrations
migrate:
	docker compose exec php bin/console doctrine:migrations:migrate
