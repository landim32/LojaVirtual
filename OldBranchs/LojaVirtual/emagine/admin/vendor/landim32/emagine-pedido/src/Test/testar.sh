#!/bin/sh
TESTE_DIR=/f/xampp/htdocs/emagine-pedido
#./vendor/bin/phpunit \
#    --bootstrap $TESTE_DIR/src/Test/config.php \
#    --testdox $TESTE_DIR/vendor/landim32/emagine-log/src/Test/LogTest.php
#./vendor/bin/phpunit \
#    --bootstrap $TESTE_DIR/src/Test/config.php \
#    --testdox $TESTE_DIR/vendor/landim32/emagine-pagamento/src/Test/PagamentoTest.php
./vendor/bin/phpunit \
    --bootstrap $TESTE_DIR/src/Test/config.php \
    --testdox $TESTE_DIR/src/Test/PedidoTest.php