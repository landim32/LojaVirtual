<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var EnderecoInfo $endereco
 * @var double $valorFrete
 * @var ProdutoInfo[] $produtos
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
                        <a href="<?php echo $urlBase . "/carrinho"; ?>" type="button" class="btn btn-primary btn-circle">2</a>
                        <p>Carrinho</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">3</button>
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
            <h3>Finalizar Pedido</h3>
            <table id="pedido" class="table table-striped table-hover table-responsive">
                <thead>
                <tr>
                    <th><a href="#">Foto</a></th>
                    <th class="hidden-xs"><a href="#">Produto</a></th>
                    <th class="text-right"><a href="#">Preço</a></th>
                    <th class="text-right"><a href="#">Quantidade</a></th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr>
                    <th class="hidden-xs">&nbsp;</th>
                    <th class="text-right">Valor Total:</th>
                    <th class="text-right" id="valorTotal"></th>
                    <th class="text-center">-</th>
                </tr>
                <?php if ($loja->getValorMinimo() > 0) : ?>
                <tr>
                    <th class="hidden-xs">&nbsp;</th>
                    <th class="text-right">Valor mínimo de compra:</th>
                    <th class="text-right"><?php echo number_format($loja->getValorMinimo(), 2, ",", "."); ?></th>
                    <th class="text-center">-</th>
                </tr>
                <?php endif; ?>
                </tfoot>
            </table>
            <hr />
            <div class="text-right">
                <a href="<?php echo $urlBase; ?>" class="btn btn-lg btn-default">
                    <i class="fa fa-chevron-left"></i> Continuar comprando
                </a>
                <a href="<?php echo $urlBase . "/pedido/entrega"; ?>" class="btn btn-lg btn-primary btn-entrega">
                    Método de Entrega <i class="fa fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
