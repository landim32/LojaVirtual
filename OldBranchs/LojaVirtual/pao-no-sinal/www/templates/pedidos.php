<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var int $cod_situacao
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo[] $pedidos
 */

$url = $app->getBaseUrl() . "/site/" . $loja->getSlug();
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-user-circle"></i> Minha Conta</li>
        <li class="active"><i class="fa fa-user-circle"></i> Alterar Dados</li>
    </ol>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?php echo $url . "/alterar-meus-dados"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-user-circle"></i> Alterar Dados
                </a>
                <a href="<?php echo $url . "/pedidos"; ?>" class="list-group-item active">
                    <i class="fa fa-fw fa-shopping-cart"></i> Pedidos feitos
                </a>
                <a href="<?php echo $url . "/lista-de-desejos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-heart"></i> Lista de Desejos
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-cart"></i> Meus Pedidos</h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Usuário</a></th>
                            <th><a href="#">Forma de Pgto</a></th>
                            <th><a href="#">Situação</a></th>
                            <th class="text-right"><a href="#">Valor Frete</a></th>
                            <th class="text-right"><a href="#">Valor</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($pedidos) > 0) : ?>
                        <?php foreach ($pedidos as $pedido) : ?>
                            <?php $urlPedido = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/pedido/" . $pedido->getId(); ?>
                            <tr>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getUsuario()->getNome(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getPagamentoStr(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>" class="<?php echo $pedido->getSituacaoHtml(); ?>"><?php echo $pedido->getSituacaoStr(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlPedido; ?>"><?php echo number_format($pedido->getValorFrete(), 2, ",", "."); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getTotalStr(); ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5"><i class="fa fa-warning"></i> Nenhum pedido cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>