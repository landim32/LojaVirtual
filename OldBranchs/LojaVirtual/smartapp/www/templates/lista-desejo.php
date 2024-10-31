<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var string $enderecoEntrega
 * @var double $valorFrete
 * @var ProdutoInfo[] $produtos
 */
$url = $app->getBaseUrl() . "/" . $loja->getSlug();

$regraPedido = new PedidoBLL();
$pagamentos = $regraPedido->listarPagamento();
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
                <a href="<?php echo $url . "/pedidos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-shopping-cart"></i> Pedidos feitos
                </a>
                <a href="<?php echo $url . "/lista-de-desejos"; ?>" class="list-group-item active">
                    <i class="fa fa-fw fa-heart"></i> Lista de Desejos
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
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
                        </tfoot>
                    </table>
                    <hr />
                    <div class="text-right">
                        <a href="<?php echo $urlHome; ?>" class="btn btn-lg btn-default">
                            <i class="fa fa-chevron-left"></i> Continuar comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
