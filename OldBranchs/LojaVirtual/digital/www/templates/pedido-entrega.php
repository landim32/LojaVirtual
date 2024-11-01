<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var ProdutoInfo[] $produtos
 * @var EnderecoInfo $endereco
 * @var UsuarioEnderecoInfo[] $enderecos
 * @var double $valorFrete
 * @var string[] $pagamentos
 */
$urlBase = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <a href="<?php echo $urlBase . "/alterar-meus-dados"; ?>" type="button" class="btn btn-warning btn-circle">1</a>
                        <p>Cadastro</p>
                    </div>
                    <div class="stepwizard-step">
                        <a href="<?php echo $urlBase . "/carrinho"; ?>" type="button" class="btn btn-warning btn-circle">2</a>
                        <p>Carrinho</p>
                    </div>
                    <div class="stepwizard-step">
                        <a href="<?php echo $urlBase . "/pedido/entrega"; ?>" type="button" class="btn btn-primary btn-circle">3</a>
                        <p>Entrega</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">4</button>
                        <p>Pagamento</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">5</button>
                        <p>Finalizar</p>
                    </div>
                </div>
            </div>
            <!--h3>Endereço de Entrega</h3-->
            <?php if (isset($erro)) : ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fa fa-warning"></i> <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-shopping-cart"></i> Resumo do Pedido
                            </h4>
                        </div>
                        <div id="resumo" class="panel-body">
                            <small>Qtde de Produtos:</small><br />
                            <strong class="quantidade">0</strong><br />
                            <small>Total:</small><br />
                            <strong>
                                <small>R$</small><span class="total">R$0,00</span>
                            </strong><br />
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <h2>Método de Entrega</h2>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                            <a href="<?php echo $urlBase . "/pedido/entrega"; ?>" class="btn btn-lg btn-primary">
                                <i class="fa fa-5x fa-truck"></i><br />Entregar em Casa
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 text-left">
                            <a href="<?php echo $urlBase . "/pedido/retirada"; ?>" class="btn btn-lg btn-default">
                                <i class="fa fa-5x fa-shopping-cart"></i><br />Retirar na Loja
                            </a>
                        </div>
                    </div>
                    <hr />
                    <h2>Endereço de Entrega</h2>
                    <div class="row">
                        <?php foreach ($enderecos as $endereco) : ?>
                            <div class="col-md-4">
                                <div id="<?php echo "endereco-" . $endereco->getId(); ?>" class="panel panel-default panel-endereco">
                                    <div class="panel-body">
                                        <h4><?php echo $endereco->getLogradouro(); ?></h4>
                                        <p>
                                            <?php echo $endereco->getEnderecoCompleto(); ?>
                                        </p>
                                    </div>
                                    <div class="panel-footer text-right">
                                        <?php startLinkEndereco($endereco, "#", "endereco-mudar btn btn-sm btn-primary"); ?>
                                        <i class="fa fa-map-marker"></i> Entregar aqui
                                        <?php endLinkEndereco(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <hr />
            <div class="text-right">
                <a href="<?php echo $urlBase . "/enderecos"; ?>" class="btn btn-lg btn-success">
                    <i class="fa fa-map-marker"></i> Alterar endereço
                </a>
                <a href="<?php echo $urlBase . "/carrinho"; ?>" class="btn btn-lg btn-default">
                    <i class="fa fa-chevron-left"></i> Voltar
                </a>
                <a href="<?php echo $urlBase . "/pedido/pagamento/entrega"; ?>" class="btn btn-lg btn-primary">
                    Efetuar pagamento <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
