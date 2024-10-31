#!/bin/sh
TESTE_DIR=/f/xampp/htdocs/emagine-produto
#./vendor/bin/phpunit \
#    --bootstrap $FRETE_DIR/src/Test/config.php \
#    --testdox $FRETE_DIR/vendor/landim32/emagine-log/src/Test/LogTest.php
#./vendor/bin/phpunit \
#    --bootstrap $FRETE_DIR/src/Test/config.php \
#    --testdox $FRETE_DIR/vendor/landim32/emagine-pagamento/src/Test/PagamentoTest.php
./vendor/bin/phpunit \
    --bootstrap $TESTE_DIR/src/Test/config.php \
    --testdox $TESTE_DIR/src/Test/ProdutoTest.php