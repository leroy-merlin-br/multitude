test:
	docker-compose run webapp vendor/bin/phpunit

coverage:
	docker-compose run webapp vendor/bin/phpunit --testsuite unit --coverage-html ./.coverage
