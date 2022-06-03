.PHONY: build
build: config up install migrate test

.PHONY: config
config:
	cd laravel && test -f .env || cp -a .env.docker .env
	cd docker/nginx && test -f localhost.pem || mkcert localhost

.PHONY: up
up:
	docker compose up -d

.PHONY: install
install:
	docker compose exec -w /var/www/html/laravel web composer install

.PHONY: migrate
migrate:
	docker compose exec web sh -c 'cd /var/www/html/laravel && php artisan migrate'

.PHONY: setup
setup:
	docker compose exec web sh -c 'cd /var/www/html/laravel && php artisan key:generate'
	docker compose exec web sh -c 'cd /var/www/html/laravel && php artisan db:seed'
	docker compose exec web sh -c 'cd /var/www/html/laravel && php artisan config:cache'
	docker compose exec web sh -c 'cd /var/www/html/laravel && rm public/storage'
	docker compose exec web sh -c 'cd /var/www/html/laravel && php artisan storage:link'
	docker compose exec web sh -c 'cd /var/www/html/laravel && yarn install'

.PHONY: start
start:
	docker compose start

.PHONY: stop
stop:
	docker compose stop

.PHONY: clean
clean:
	docker compose down

.PHONY: reset
reset:
	docker compose down -v

.PHONY: test
test:
	@curl -k https://localhost:10443/ -f -I && echo '[test] OK'

.PHONY: open
open:
	open https://localhost:10443/