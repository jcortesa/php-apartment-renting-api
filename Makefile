BACKEND_CONTAINER=api-backend

help:
	@echo "🛠️  Available targets:"
	@echo "  🚀  build           - Build and start the containers"
	@echo "  ▶️  up              - Start the containers in detached mode"
	@echo "  ⏹️  down            - Stop and remove the containers"
	@echo "  🖥️  bash            - Open a bash shell in the backend container"
	@echo "  📊  coverage-test   - Run tests and generate a coverage report"
	@echo "  🧪  unit-test       - Run unit tests in the backend container"
	@echo "  🧪  acceptance-test - Run acceptance tests in the backend container"

.PHONY: build up down bash

build:
	docker compose up --build

up:
	docker compose up -d

down:
	docker compose down

bash:
	docker exec -ti $(BACKEND_CONTAINER) bash

coverage-test:
	docker exec -it $(BACKEND_CONTAINER) php bin/phpunit --testsuite "Project Unit Test Suite" --coverage-html build/coverage --coverage-text

unit-test:
	docker exec -it $(BACKEND_CONTAINER) php bin/phpunit --testsuite "Project Unit Test Suite"

acceptance-test:
	docker exec -it $(BACKEND_CONTAINER) php bin/phpunit --testsuite "Project Acceptance Test Suite"
