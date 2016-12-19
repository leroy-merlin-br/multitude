test:
	docker-compose run webapp vendor/bin/phpunit

coverage:
	docker-compose run webapp vendor/bin/phpunit --testsuite unit --coverage-html ./.coverage

test-ci:
	docker-compose run webapp vendor/bin/phpunit --testsuite unit --coverage-clover ./.coverage/coverage-clover.xml
	docker-compose run webapp vendor/bin/phpunit --testsuite functional
