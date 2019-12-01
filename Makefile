u:
	./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests --filter testLargeProcessText;

all:
	./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests;

