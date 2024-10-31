#!/bin/sh
TESTE_DIR=/f/xampp/htdocs/emagine-loja
./vendor/bin/phpunit \
    --bootstrap $TESTE_DIR/test/config.php \
    --testdox $TESTE_DIR/vendor/landim32/emagine-log/src/Test/LogTest.php
./vendor/bin/phpunit \
    --bootstrap $TESTE_DIR/test/config.php \
    --testdox $TESTE_DIR/vendor/landim32/emagine-produto/src/Test/ProdutoTest.php
./vendor/bin/phpunit \
    --bootstrap $TESTE_DIR/test/config.php \
    --testdox $TESTE_DIR/vendor/landim32/emagine-pagamento/src/Test/PagamentoTest.php
./vendor/bin/phpunit \
    --bootstrap $TESTE_DIR/test/config.php \
    --testdox $TESTE_DIR/vendor/landim32/emagine-pedido/src/Test/PedidoTest.php