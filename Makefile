.PHONY: verify phpunit phpcs phpstan

verify: phpunit phpcs phpstan

phpunit:
	vendor/bin/phpunit

phpcs:
	vendor/bin/phpcs -p

phpstan:
	vendor/bin/phpstan --level=8 analyse src/ tests/
