<?php

namespace Emagine\Pedido;

use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Base\EmagineApp;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var int $cod_situacao
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var PedidoInfo[] $pedidos
 */

$regraPedido = new PedidoBLL();
$situacoes = $regraPedido->listarSituacao();

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <div class="list-group">
                <?php $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedidos"; ?>
                <?php if ($cod_situacao == 0) : ?>
                    <a href="<?php echo $url; ?>" class="list-group-item active"><i class="fa fa-fw fa-check-circle"></i> Todos</a>
                <?php else : ?>
                    <a href="<?php echo $url; ?>" class="list-group-item"><i class="fa fa-fw fa-circle-o"></i> Todos</a>
                <?php endif; ?>
                <?php foreach ($situacoes as $codSituacao => $nomeSituacao) : ?>
                    <?php $url = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedidos/" . $codSituacao; ?>
                    <?php if ($codSituacao == $cod_situacao) : ?>
                        <a href="<?php echo $url; ?>" class="list-group-item active"><i class="fa fa-fw fa-check-circle"></i> <?php echo $nomeSituacao; ?></a>
                    <?php else : ?>
                        <a href="<?php echo $url; ?>" class="list-group-item"><i class="fa fa-fw fa-circle-o"></i> <?php echo $nomeSituacao; ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-cart"></i> Pedidos</h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive datatable">
                        <thead>
                        <tr>
                            <th><a href="#">Nº</a></th>
                            <th><a href="#">Cliente</a></th>
                            <th><a href="#">Dt Emissão</a></th>
                            <th><a href="#">Pgto</a></th>
                            <th><a href="#">Entrega</a></th>
                            <th class="text-right"><a href="#">Valor</a></th>
                            <th><a href="#">Situação</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($pedidos) > 0) : ?>
                        <?php foreach ($pedidos as $pedido) : ?>
                            <?php $urlPedido = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/id/" . $pedido->getId(); ?>
                            <tr>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo '#' . $pedido->getId(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getUsuario()->getNome(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getDataInclusaoStr(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getPagamentoStr(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getEntregaStr(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getTotalStr(); ?></a></td>
                                <td>
                                    <?php if ($pedido->getCodSituacao() == PedidoInfo::CANCELADO) : ?>
                                        <a href="<?php echo $urlPedido; ?>" class="<?php echo $pedido->getSituacaoHtml(); ?>">
                                            <?php echo $pedido->getSituacaoStr(); ?>
                                        </a>
                                    <?php else : ?>
                                        <?php
                                        switch ($pedido->getCodSituacao()) {
                                            case PedidoInfo::PENDENTE:
                                                $classe = "btn btn-xs btn-danger dropdown-toggle";
                                                break;
                                            case PedidoInfo::AGUARDANDO_PAGAMENTO:
                                                $classe = "btn btn-xs btn-warning dropdown-toggle";
                                                break;
                                            case PedidoInfo::ENTREGANDO:
                                                $classe = "btn btn-xs btn-warning dropdown-toggle";
                                                break;
                                            case PedidoInfo::ENTREGUE:
                                                $classe = "btn btn-xs btn-primary dropdown-toggle";
                                                break;
                                            case PedidoInfo::FINALIZADO:
                                                $classe = "btn btn-xs btn-success dropdown-toggle";
                                                break;
                                            default:
                                                $classe = "btn btn-xs btn-default dropdown-toggle";
                                                break;
                                        }
                                        ?>
                                        <div class="dropdown">
                                            <button class="<?php echo $classe; ?>" type="button" data-toggle="dropdown">
                                                <?php echo $pedido->getSituacaoStr(); ?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php foreach ($situacoes as $cod_situacao => $situacao) : ?>
                                                    <?php $urlSituacao = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/situacao/" . $pedido->getId() . "/" . $cod_situacao; ?>
                                                    <li><a href="<?php echo $urlSituacao; ?>"><?php echo $situacao; ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7"><i class="fa fa-warning"></i> Nenhum pedido cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>