#!/bin/sh
RESULT_FILE="check_code.result.cache"
rm -f -- $RESULT_FILE
touch $RESULT_FILE

echo "Installing dependencies..."
{
  ./vendor/bin/phpcs --config-set installed_paths "$(realpath vendor/escapestudios/symfony2-coding-standard)"
  ./vendor/bin/phpcs --config-set default_standard Symfony
} > /dev/null 2>&1
rm -f -- .php-cs-fixer.dist.php
rm -f -- .php-cs-fixer.cache

echo "Running php-cs-fixer..."
./vendor/bin/php-cs-fixer fix src/ -vvv --rules=@Symfony,@PSR1,@PSR2,@PSR12 >> $RESULT_FILE
rm -f -- .php-cs-fixer.dist.php
rm -f -- .php-cs-fixer.cache

echo "Running phpcs..."
./vendor/bin/phpcs --standard=Symfony src/ --ignore=Kernel.php >> $RESULT_FILE

echo "Running debug:translation..."
{
  ./bin/console debug:translation en --only-missing
  ./bin/console debug:translation pl --only-missing
} >> $RESULT_FILE

echo "Running DB schema and data fixtures..."
{
  ./bin/console doctrine:schema:drop --no-interaction --full-database --force
  ./bin/console doctrine:migrations:migrate --no-interaction
  ./bin/console doctrine:fixtures:load --no-interaction
}  >> $RESULT_FILE