<?php

namespace Emagine\Loja;

use Emagine\Produto\Model\LojaInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pedido\Model\PedidoInfo;

/**
 * @var LojaInfo $loja
 * @var UsuarioInfo $usuario
 * @var PedidoInfo $pedido
 * @var string $urlHome;
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Pedido finalizado com sucesso.</h3>
                    <p>Obrigada por comprar na AJ Supermercado! Seu pedido foi confirmado com sucesso. Agora vamos separar seus produtos e em breve estaremos entregando em sua casa. Aguarde mais informações.</p>
                    <hr />
                    <div class="text-right">
                        <a href="<?php echo $urlHome; ?>" class="btn btn-lg btn-primary">
                            <i class="fa fa-chevron-left"></i> Voltar ao site
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
