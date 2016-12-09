test:
	docker-compose run webapp vendor/bin/phpunit --testsuite unit
	docker-compose run webapp vendor/bin/phpunit --testsuite functional --testdox

coverage:
	docker-compose run webapp vendor/bin/phpunit --testsuite unit --coverage-html ./.coverage
