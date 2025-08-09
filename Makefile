BACKEND_CONTAINER=api-backend

help:
	@echo "🛠️  Available targets:"
	@echo "  🚀  build           - Build and start the containers"
	@echo "  ▶️  up              - Start the containers in detached mode"
	@echo "  ⏹️  down            - Stop and remove the containers"
	@echo "  🖥️  bash            - Open a bash shell in the backend container"
	@echo "  🧪  test-unit       - Run unit tests in the backend container"
	@echo "  🧪  test-acceptance - Run acceptance tests in the backend container"

.PHONY: build up down bash

build:
	docker compose up --build

up:
	docker compose up -d

down:
	docker compose down

bash:
	docker exec -ti $(BACKEND_CONTAINER) bash

test-unit:
	docker exec -it $(BACKEND_CONTAINER) php bin/phpunit

test-acceptance:
	docker exec -it $(BACKEND_CONTAINER) php bin/phpunit --group=acceptance
