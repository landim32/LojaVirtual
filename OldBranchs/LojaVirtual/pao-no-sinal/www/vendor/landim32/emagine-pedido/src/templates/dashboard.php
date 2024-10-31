<?php
namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Pedido\Model\ProdutoVendidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var int $mes
 * @var int $ano
 * @var string $mesAtual
 * @var string $mesAtualStr
 * @var array<string,string> $mesAtividade
 * @var PedidoInfo[] $pedidos
 * @var ProdutoVendidoInfo[] $produtos
 * @var string $graficoProdutoBase64
 */
$urlVendaMes = sprintf($app->getBaseUrl() . "/grafico/venda-em-%s-%s.png", $mes, $ano);
if (!is_null($loja)) {
    $urlVendaMes .= "?loja=" . $loja->getId();
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <?php if (count($mesAtividade) > 1) : ?>
            <div class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <?php echo $mesAtualStr; ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <?php foreach ($mesAtividade as $key => $value) : ?>
                        <?php $url = $app->getBaseUrl() . "/dashboard/" . date("m-Y", strtotime($key)); ?>
                        <li><a href="<?php echo $url; ?>"><?php echo $value; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <br />
            <?php endif; ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-line-chart"></i> Vendas em <?php echo $mesAtualStr; ?></h3>
                </div>
                <div class="panel-body">
                    <img src="<?php echo $urlVendaMes; ?>" class="img-responsive" alt="Vendas em <?php echo $mesAtualStr; ?>" />
                </div>
            </div>
            <?php if (count($produtos) > 0) : ?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-pie-chart"></i> Mais vendidos em <?php echo $mesAtualStr; ?></h3>
                    </div>
                    <div class="panel-body">
                        <img src="<?php echo $graficoProdutoBase64; ?>" class="img-responsive" alt="..." />
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-dollar"></i> Vendas de Hoje</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th class="text-right">Pedido Nº</th>
                                    <th class="text-right"><i class="fa fa-dollar"></i> Smart</th>
                                    <th class="text-right"><i class="fa fa-dollar"></i> Loja</th>
                                    <th class="text-right"><i class="fa fa-dollar"></i> Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($pedidos) > 0) : ?>
                                    <?php
                                    $totalComissao = 0;
                                    $totalLoja = 0;
                                    $total = 0;
                                    ?>
                                    <?php foreach ($pedidos as $pedido) : ?>
                                        <?php
                                        $url = $app->getBaseUrl() . "/" . $pedido->getLoja()->getSlug() . "/pedido/id/" . $pedido->getId();
                                        $comissao = $pedido->getTotal() * 0.08;
                                        $totalComissao += $comissao;
                                        $vlrLoja = $pedido->getTotal() * 0.92;
                                        $totalLoja += $vlrLoja;
                                        $total += $pedido->getTotal();
                                        ?>
                                        <tr>
                                            <td class="text-right"><a href="<?php echo $url; ?>"><?php echo "#" . $pedido->getId(); ?></a></td>
                                            <td class="text-right"><?php echo number_format($comissao, 2, ",", "."); ?></td>
                                            <td class="text-right"><?php echo number_format($vlrLoja, 2, ",", "."); ?></td>
                                            <td class="text-right"><?php echo $pedido->getTotalStr(); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <th class="text-right">Total:</th>
                                        <th class="text-right"><?php echo number_format($totalComissao, 2, ",", "."); ?></th>
                                        <th class="text-right"><?php echo number_format($totalLoja, 2, ",", "."); ?></th>
                                        <th class="text-right"><?php echo number_format($total, 2, ",", "."); ?></th>
                                    </tr>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4">
                                            <i class="fa fa-warning"></i> Nenhuma venda feita hoje!
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>
    </div>
</div>