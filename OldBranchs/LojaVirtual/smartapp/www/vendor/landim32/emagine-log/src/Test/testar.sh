#!/bin/sh
./vendor/bin/phpunit \
    --bootstrap /f/xampp/htdocs/emagine-log/src/Test/config.php \
    --testdox /f/xampp/htdocs/emagine-log/src/Test/LogTest.php