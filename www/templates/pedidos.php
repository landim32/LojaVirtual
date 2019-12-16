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

$url = $app->getBaseUrl() . "/" . $loja->getSlug();
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
                <a href="<?php echo $url . "/enderecos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-map-marker"></i> Endereços
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
                    <div class="row">
                        <div class="col-md-6">
                            <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-cart"></i> Meus Pedidos</h3>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" class="form-horizontal">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="data_ini" class="form-control datepicker" placeholder="Início" aria-describedby="basic-addon1">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" name="data_fim" class="form-control datepicker" placeholder="Termíno" aria-describedby="basic-addon1">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Forma de Pgto</a></th>
                            <th><a href="#">Situação</a></th>
                            <th class="text-right"><a href="#">Data</a></th>
                            <th class="text-right"><a href="#">Valor Frete</a></th>
                            <th class="text-right"><a href="#">Valor</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($pedidos) > 0) : ?>
                        <?php foreach ($pedidos as $pedido) : ?>
                            <?php
                                $urlPedido = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/" . $pedido->getId();
                                $urlAvaliacao = $app->getBaseUrl() . "/" . $loja->getSlug() . "/avaliar/" . $pedido->getId();
                                ?>
                            <tr>
                                <td><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getPagamentoStr(); ?></a></td>
                                <td><a href="<?php echo $urlPedido; ?>" class="<?php echo $pedido->getSituacaoHtml(); ?>"><?php echo $pedido->getSituacaoStr(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getDataInclusaoStr(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlPedido; ?>"><?php echo number_format($pedido->getValorFrete(), 2, ",", "."); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlPedido; ?>"><?php echo $pedido->getTotalStr(); ?></a></td>
                                <td class="text-right">
                                    <?php if (in_array($pedido->getCodSituacao(), array(PedidoInfo::ENTREGUE, PedidoInfo::FINALIZADO))) : ?>
                                        <a href="<?php echo $urlAvaliacao; ?>" class="btn btn-xs btn-primary">
                                            <i class="fa fa-star"></i> Avaliar
                                        </a>
                                    <?php else : ?>
                                        <a href="#" class="btn btn-xs btn-default" disabled="disabled">
                                            <i class="fa fa-star"></i> Avaliar
                                        </a>
                                    <?php endif; ?>
                                </td>
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