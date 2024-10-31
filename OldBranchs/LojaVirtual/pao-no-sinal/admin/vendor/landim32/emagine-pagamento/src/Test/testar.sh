#!/bin/sh
./vendor/bin/phpunit \
    --bootstrap /f/xampp/htdocs/emagine-pagamento/src/Test/config.php \
    --testdox /f/xampp/htdocs/emagine-pagamento/src/Test/PagamentoTest.php