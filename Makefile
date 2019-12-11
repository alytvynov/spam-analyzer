u:
	./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests/SpamAnalyzerTest;

all:
	./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests;

t:
	./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests/AnalyzeTextTest;

a:
	./vendor/bin/phpunit  --verbose --bootstrap  vendor/autoload.php tests/DataProviderTest;
