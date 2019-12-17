<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 */

$cliente = $pedido->getUsuario();
$url = $app->getBaseUrl() . "/site/" . $loja->getSlug();
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-user-circle"></i> Minha Conta</li>
        <li><a href="<?php echo $url . "/pedidos"; ?>"><i class="fa fa-shopping-cart"></i> Pedidos</a></li>
        <li class="active"><i class="fa fa-shopping-basket"></i> Pedido #<?php echo $pedido->getId(); ?></li>
    </ol>
    <div class="row">
        <div class="col-md-3">
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
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-md-10">
                            <h2>
                                Pedido #<?php echo $pedido->getId(); ?>
                                (R$ <?php echo $pedido->getTotalStr(); ?>)
                            </h2>
                            <span class="badge"><?php echo $pedido->getPagamentoStr(); ?></span>
                            <span class="badge"><?php echo $pedido->getSituacaoStr(); ?></span>
                        </div>
                    </div>
                    <hr />
                    <dl class="dl-horizontal">
                        <dt>Cliente:</dt>
                        <dd><?php echo $cliente->getNome(); ?></dd>
                        <?php if (!isNullOrEmpty($cliente->getTelefone())) : ?>
                            <dt>Telefone:</dt>
                            <dd><?php echo $cliente->getTelefone(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($cliente->getCep())) : ?>
                            <dt>CEP:</dt>
                            <dd><?php echo $pedido->getCep(); ?></dd>
                        <?php endif; ?>
                        <dt>Logradouro:</dt>
                        <dd><?php echo $pedido->getLogradouro(); ?></dd>
                        <?php if (!isNullOrEmpty($cliente->getComplemento())) : ?>
                            <dt>Complemento:</dt>
                            <dd><?php echo $pedido->getComplemento(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($cliente->getNumero())) : ?>
                            <dt>Numero:</dt>
                            <dd><?php echo $pedido->getNumero(); ?></dd>
                        <?php endif; ?>
                        <dt>Bairro:</dt>
                        <dd><?php echo $pedido->getBairro(); ?></dd>
                        <dt>Cidade:</dt>
                        <dd><?php echo $pedido->getCidade(); ?></dd>
                        <dt>UF:</dt>
                        <dd><?php echo $pedido->getUf(); ?></dd>
                    </dl>
                    <hr />
                    <dl class="dl-horizontal">
                        <dt>Forma de Pgto:</dt>
                        <dd><?php echo $pedido->getPagamentoStr(); ?></dd>
                        <dt>Situação:</dt>
                        <dd><?php echo $pedido->getSituacaoStr(); ?></dd>
                    </dl>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Produto</th>
                            <th class="text-right">Preço</th>
                            <th class="text-right">Quantidade</th>
                            <th class="text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $quantidade = 0;
                        $total = $pedido->getValorFrete();
                        ?>
                        <?php foreach ($pedido->listarItens() as $pedidoItem) : ?>
                            <?php
                            $produto = $pedidoItem->getProduto();
                            $urlFoto = $app->getBaseUrl() . "/produto/20x20/" . $produto->getFoto();
                            $valorItem = ($produto->getValorPromocao() > 0) ? $produto->getValorPromocao() : $produto->getValor();
                            $totalItem = $valorItem * $pedidoItem->getQuantidade();
                            $quantidade += $pedidoItem->getQuantidade();
                            $total += $totalItem;
                            ?>
                            <tr>
                                <td><img src="<?php echo $urlFoto; ?>" class="img-responsive" alt="<?php echo $produto->getNome(); ?>" /></td>
                                <td><?php echo $produto->getNome(); ?></td>
                                <td class="text-right"><?php echo number_format($valorItem, 2, ",", "."); ?></td>
                                <td class="text-right"><?php echo $pedidoItem->getQuantidade(); ?></td>
                                <td class="text-right"><?php echo number_format($totalItem, 2, ",", "."); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-right" colspan="3">Valor Frete:</th>
                            <th class="text-right">-</th>
                            <th class="text-right"><?php echo number_format($pedido->getValorFrete(), 2, ",", "."); ?></th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="3">Total:</th>
                            <th class="text-right"><?php echo number_format($quantidade, 0, ",", "."); ?></th>
                            <th class="text-right"><?php echo number_format($total, 2, ",", "."); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>