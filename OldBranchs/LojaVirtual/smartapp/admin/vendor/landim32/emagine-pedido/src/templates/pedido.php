<?php

namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string[] $situacoes
 * @var string $erro
 */

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render() ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <h2 style="margin-top: 5px; margin-bottom: 5px;">Pedido #<?php echo $pedido->getId(); ?></h2>
                                        </div>
                                        <div class="col-md-5 text-right">
                                            <?php if ($pedido->getCodSituacao() != PedidoInfo::CANCELADO) : ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                                        <?php echo $pedido->getSituacaoStr(); ?>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
                                                        <li>
                                                            <a href="#situacaoModal" data-toggle="modal" data-target="#situacaoModal">
                                                                <i class="fa fa-envelope"></i> Enviar Mensagem
                                                            </a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <?php foreach ($situacoes as $cod_situacao => $situacao) : ?>
                                                            <?php $urlSituacao = $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/situacao/" . $pedido->getId() . "/" . $cod_situacao; ?>
                                                            <li><a href="<?php echo $urlSituacao; ?>"><?php echo $situacao; ?></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="badge"><?php echo $pedido->getEntregaStr(); ?></span>
                                    <span class="badge"><?php echo $pedido->getPagamentoStr(); ?></span>
                                    <span class="badge"><?php echo $pedido->getSituacaoStr(); ?></span>
                                </div>
                            </div>
                            <hr />
                            <?php if (!isNullOrEmpty($erro)) : ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <i class="fa fa-warning"></i> <?php echo $erro; ?>
                                </div>
                            <?php endif; ?>
                            <?php require __DIR__ . "/pedido-dado.php"; ?>
                            <div class="text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedidos"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <div>
                        <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/imprimir/" . $pedido->getId(); ?>" target="_blank"><i class="fa fa-fw fa-print"></i> Imprimir</a><br />
                        <?php if ($pedido->getCodSituacao() != PedidoInfo::CANCELADO) : ?>
                        <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/situacao/" . $pedido->getId() . "/" . $pedido->getProximaAcao(); ?>"><?php echo $pedido->getProximaAcaoStr(); ?></a><br />
                        <?php endif; ?>
                        <?php if ($pedido->getIdPagamento() > 0) : ?>
                        <a href="<?php echo $app->getBaseUrl() . "/pagamento/" . $pedido->getIdPagamento(); ?>"><i class="fa fa-fw fa-money"></i> Dados de Pagamento</a><br />
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>