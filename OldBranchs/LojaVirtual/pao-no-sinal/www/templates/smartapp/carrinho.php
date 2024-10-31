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
$urlHome = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
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
                    <td class="hidden-xs">&nbsp;</td>
                    <td class="text-right">Entrega em <b><?php echo $endereco->getEnderecoCompleto(false, false); ?></b>. <b>Frete:</b></td>
                    <th class="text-right"><?php echo number_format($valorFrete, 2, ",", "."); ?></th>
                    <th class="text-center">-</th>
                </tr>
                <tr>
                    <th class="hidden-xs">&nbsp;</th>
                    <th class="text-right">Valor Total:</th>
                    <th class="text-right" id="valorTotal"></th>
                    <th class="text-center">-</th>
                </tr>
                <?php if ($loja->getValorMinimo() > 0) : ?>
                    <tr>
                        <th class="hidden-xs">&nbsp;</th>
                        <th class="text-right">Valor Mínimo da Compra:</th>
                        <th class="text-right"><?php echo $loja->getValorMinimoStr(); ?></th>
                        <th class="text-center">-</th>
                    </tr>
                <?php endif; ?>
                </tfoot>
            </table>
            <form method="POST" class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-7">
                        <label class="control-label" for="observacao">Observação:</label>
                        <textarea id="observacao" name="observacao" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="cod_pagamento">Forma de Pagamento</label>
                        <select id="cod_pagamento" name="cod_pagamento" class="form-control">
                            <?php foreach ($pagamentos as $cod_pagamento => $pagamento) : ?>
                                <option value="<?php echo $cod_pagamento; ?>"><?php echo $pagamento; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label" for="troco_para">Troco para:</label>
                        <input type="text" id="troco_para" name="troco_para" class="form-control money" value="0,00" />
                    </div>
                </div>
            </form>
            <hr />
            <div class="text-right">
                <a href="<?php echo $urlHome; ?>" class="btn btn-lg btn-default">
                    <i class="fa fa-chevron-left"></i> Continuar comprando
                </a>
                <button type="button" class="btn btn-lg btn-primary btn-finalizar" data-loading-text="Enviando...">
                    Finalizar Pedido <i class="fa fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
