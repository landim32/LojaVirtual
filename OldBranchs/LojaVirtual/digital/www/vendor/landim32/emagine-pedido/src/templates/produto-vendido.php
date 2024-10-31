<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pedido\Model\ProdutoVendidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var bool $usaFoto
 * @var LojaInfo|null $loja
 * @var ProdutoVendidoInfo[] $produtos
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render($app->getBaseUrl() . "/%s/produto-vendido") ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-cart"></i> Ranking de Produtos Vendidos</h3>
                    <hr />
                    <form method="POST" class="form-horizontal">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input type="text" name="p" class="form-control" placeholder="Busca por palavra-chave" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="data_ini" class="form-control datepicker" placeholder="Início" aria-describedby="basic-addon1">
                                    <span class="input-group-addon">até</span>
                                    <input type="text" name="data_fim" class="form-control datepicker" placeholder="Termíno" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Produto <i class="fa fa-chevron-down"></i></a></th>
                            <th class="text-right"><a href="#">Preço</a></th>
                            <th class="text-right"><a href="#">Vendas</a></th>
                            <th class="text-right"><a href="#">Prod. Vendidos</a></th>
                            <th class="text-right"><a href="#">Vlr Total Vendido</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $lojaAtual = null;
                        $totalLoja = 0;
                        $total = 0;
                        ?>
                        <?php if (count($produtos) > 0) : ?>
                        <?php foreach ($produtos as $produto) : ?>
                            <?php
                                $url = $app->getBaseUrl() . "/" . $produto->getLojaSlug() .
                                    "/produto/" . $produto->getProdutoSlug();
                            ?>
                            <?php if ($lojaAtual != $produto->getLoja()) : ?>
                                <?php if ($totalLoja > 0) : ?>
                                    <tr>
                                        <th colspan="4" class="text-right">Total:</th>
                                        <th class="text-right"><?php echo number_format($totalLoja, 2, ",", "."); ?></th>
                                    </tr>
                                    <?php $totalLoja = 0; ?>
                                <?php endif; ?>
                                <?php $lojaAtual = $produto->getLoja(); ?>
                                <tr>
                                    <th colspan="5"><?php echo $lojaAtual; ?></th>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td><a href="<?php echo $url; ?>"><?php echo $produto->getProduto(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $url; ?>"><?php echo $produto->getPrecoStr(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $url; ?>"><?php echo $produto->getVendas(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $url; ?>"><?php echo $produto->getQuantidade(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $url; ?>"><?php echo $produto->getValorTotalStr(); ?></a></td>
                            </tr>
                            <?php
                                $totalLoja += $produto->getValorTotal();
                                $total += $produto->getValorTotal();
                            ?>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7"><i class="fa fa-warning"></i> Nenhum cliente cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th colspan="4" class="text-right">Total Geral:</th>
                            <th class="text-right"><?php echo number_format($total, 2, ",", "."); ?></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>