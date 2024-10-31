<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Pagamento\Controls\PagamentoForm;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PagamentoInfo $pagamento
 * @var string $metodo
 * @var string[] $pagamentos
 */
$urlBase = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<!--pre><?php //print_r($pagamento); ?></pre-->
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
                        <a href="<?php echo $urlBase . "/pedido/entrega"; ?>" type="button" class="btn btn-warning btn-circle">3</a>
                        <p>Entrega</p>
                    </div>
                    <div class="stepwizard-step">
                        <a href="<?php echo $urlBase . "/pedido/pagamento"; ?>" class="btn btn-primary btn-circle">4</a>
                        <p>Pagamento</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">5</button>
                        <p>Finalizar</p>
                    </div>
                </div>
            </div>
            <form id="pagamento" method="post" class="form-horizontal">
                <input type="hidden" id="id_endereco" name="id_endereco" value="" />
                <input type="hidden" id="pedido" name="pedido" value="" />
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
                                <hr />
                                <?php if ($metodo == "entrega") : ?>
                                    <small>Método de Entrega:</small><br />
                                    <strong class="metodo">Entregar em Casa</strong><br />
                                    <small>Endereço:</small><br />
                                    <strong class="endereco-atual">
                                        <span class="completo"></span>
                                    </strong><br />
                                <?php else : ?>
                                    <small>Método de Entrega:</small><br />
                                    <strong class="metodo">Retirar na Loja</strong><br />
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h2>Efetuar Pagamento</h2>
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>
                        <?php require __DIR__ . "/pagamento-form.php"; ?>
                    </div>
                </div>
                <hr />
                <div class="text-right">
                    <a href="<?php echo $urlBase . "/pedido/entrega"; ?>" class="btn btn-lg btn-default">
                        <i class="fa fa-chevron-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-lg btn-primary">
                        Pagar <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
