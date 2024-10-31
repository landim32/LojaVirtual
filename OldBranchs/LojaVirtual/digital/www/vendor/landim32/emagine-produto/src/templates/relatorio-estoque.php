<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var ProdutoInfo[] $produtos
 * @var LojaInfo $loja
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render($app->getBaseUrl() . "/%s/relatorio/estoque"); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-cart"></i> Relatório de Estoque</h3>
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Foto</a></th>
                            <th><a href="#">Nome</a></th>
                            <th class="text-right"><a href="#">Quantidade</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($produtos as $produto) : ?>
                            <?php $urlProduto = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug(); ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $urlProduto; ?>">
                                        <img src="<?php echo $app->getBaseUrl() . "/produto/30x30/" . $produto->getFoto(); ?>" class="img-circle" />
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo $urlProduto; ?>">
                                        <?php echo $produto->getNome(); ?>
                                    </a>
                                    <?php if ($produto->getDestaque() == true) : ?>
                                        <label class="label label-info">Destaque</label>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($produto->getQuantidade() > $loja->getEstoqueMinimo()): ?>
                                        <a href="<?php echo $urlProduto; ?>">
                                            <?php echo $produto->getQuantidade(); ?>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo $urlProduto; ?>" class="text-danger">
                                            <?php echo $produto->getQuantidade(); ?>
                                            <i class="fa fa-warning"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>