<?php
namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 * @var string[] $situacoes
 * @var string $erro
 */

$cliente = $pedido->getUsuario();
?>
<dl class="dl-horizontal">
    <?php if ($pedido->getNota() > 0) : ?>
        <dt>Nota:</dt>
        <dd><?php echo $pedido->getNotaHtml(); ?></dd>
    <?php endif; ?>
    <dt>Data Emissão:</dt>
    <dd><?php echo $pedido->getDataInclusaoStr(); ?></dd>
    <dt>Última alteração:</dt>
    <dd><?php echo $pedido->getUltimaAlteracaoStr(); ?></dd>
    <dt>Cliente:</dt>
    <dd><?php echo $cliente->getNome(); ?></dd>
    <?php if (!isNullOrEmpty($cliente->getEmail())) : ?>
        <dt>Email:</dt>
        <dd><a href="mailto:<?php echo $cliente->getEmail(); ?>"><?php echo $cliente->getEmail(); ?></a></dd>
    <?php endif; ?>
    <?php if (!isNullOrEmpty($cliente->getTelefone())) : ?>
        <dt>Telefone:</dt>
        <dd><?php echo $cliente->getTelefoneStr(); ?></dd>
    <?php endif; ?>
    <?php if (!isNullOrEmpty($pedido->getCep())) : ?>
        <dt>CEP:</dt>
        <dd><?php echo $pedido->getCep(); ?></dd>
    <?php endif; ?>
    <dt>Logradouro:</dt>
    <dd><?php echo $pedido->getLogradouro(); ?></dd>
    <?php if (!isNullOrEmpty($pedido->getComplemento())) : ?>
        <dt>Complemento:</dt>
        <dd><?php echo $pedido->getComplemento(); ?></dd>
    <?php endif; ?>
    <?php if (!isNullOrEmpty($pedido->getNumero())) : ?>
        <dt>Numero:</dt>
        <dd><?php echo $pedido->getNumero(); ?></dd>
    <?php endif; ?>
    <dt>Bairro:</dt>
    <dd><?php echo $pedido->getBairro(); ?></dd>
    <dt>Cidade:</dt>
    <dd><?php echo $pedido->getCidade(); ?></dd>
    <dt>UF:</dt>
    <dd><?php echo $pedido->getUf(); ?></dd>
    <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
        <dt>Horário de Entrega:</dt>
        <dd><?php echo $pedido->getDataEntregaStr(); ?></dd>
    <?php endif; ?>
<?php if (!isNullOrEmpty($pedido->getObservacao())) : ?>
    <hr />
    <dl class="dl-horizontal">
        <dt>Observação:</dt>
        <dd><?php echo $pedido->getObservacao(); ?></dd>
    </dl>
<?php endif; ?>
<?php if (!isNullOrEmpty($pedido->getComentario())) : ?>
    <hr />
    <dl class="dl-horizontal">
        <dt>Comentário:</dt>
        <dd><?php echo $pedido->getComentario(); ?></dd>
    </dl>
<?php endif; ?>
</dl>
<hr />
<table class="table table-striped table-hover table-responsive">
    <thead>
    <tr>
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
        $valorItem = ($produto->getValorPromocao() > 0) ? $produto->getValorPromocao() : $produto->getValor();
        $totalItem = $valorItem * $pedidoItem->getQuantidade();
        $quantidade += $pedidoItem->getQuantidade();
        $total += $totalItem;
        ?>
        <tr>
            <td><?php echo $produto->getNome(); ?></td>
            <td class="text-right"><?php echo number_format($valorItem, 2, ",", "."); ?></td>
            <td class="text-right"><?php echo $pedidoItem->getQuantidade(); ?></td>
            <td class="text-right"><?php echo number_format($totalItem, 2, ",", "."); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <th class="text-right" colspan="2">Valor Frete:</th>
        <th class="text-right">-</th>
        <th class="text-right"><?php echo number_format($pedido->getValorFrete(), 2, ",", "."); ?></th>
    </tr>
    <tr>
        <th class="text-right" colspan="2">Total:</th>
        <th class="text-right"><?php echo number_format($quantidade, 0, ",", "."); ?></th>
        <th class="text-right"><?php echo number_format($total, 2, ",", "."); ?></th>
    </tr>
    <?php if ($pedido->getTrocoPara() > 0) : ?>
        <tr>
            <th class="text-right" colspan="2">Troco para:</th>
            <th class="text-right"><?php echo number_format($pedido->getTrocoPara(), 2, ",", "."); ?></th>
            <th class="text-right"><?php echo $pedido->getTrocoStr(); ?></th>
        </tr>
    <?php endif; ?>
    </tfoot>
</table>