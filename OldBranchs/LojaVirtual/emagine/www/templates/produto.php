<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
/**
 * @var EmagineApp $app;
 * @var LojaInfo $loja
 * @var CategoriaInfo $categoria
 * @var ProdutoInfo $produto
 * @var string $paginacao
 */
$categorias = array();
$categoriaAtual = $categoria;
while (!is_null($categoriaAtual)) {
    array_push($categorias, $categoriaAtual);
    $categoriaAtual = $categoriaAtual->getPai();
}
/** @var CategoriaInfo[] $categorias */
$categorias = array_reverse($categorias);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() ?>">
                        <i class="fa fa-home"></i> Home
                    </a>
                </li>
                <?php foreach ($categorias as $categoriaLocal) : ?>
                <li>
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/" . $categoriaLocal->getSlug() ?>">
                        <i class="fa fa-chevron-right"></i> <?php echo $categoriaLocal->getNome(); ?>
                    </a>
                </li>
                <?php endforeach; ?>
                <li class="active">
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/" . $categoria->getSlug() . "/" . $produto->getSlug(); ?>">
                        <i class="fa fa-chevron-right"></i> <?php echo $produto->getNome(); ?>
                    </a>
                </li>
            </ol>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="thumbnail">
                <img src="<?php echo $produto->getFotoUrl(250, 250); ?>" class="img-responsive"
                     style="width: 250px; height: 250px" alt="<?php echo $produto->getNome(); ?>" />
                <div class="caption text-center">
                    <div class="btn-adicionar"
                         data-id="<?php echo $produto->getId(); ?>"
                         data-loja="<?php echo $produto->getIdLoja(); ?>"
                         data-foto="<?php echo $produto->getFoto(); ?>"
                         data-nome="<?php echo $produto->getNome(); ?>"
                         data-valor="<?php echo number_format( $produto->getValor(), 2, ".", ""); ?>"
                         data-promocao="<?php echo number_format( $produto->getValorPromocao(), 2, ".", ""); ?>"
                    ></div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="sharethis-inline-share-buttons"></div>
                    <h1><?php echo $produto->getNome(); ?></h1>
                    <?php if (!isNullOrEmpty($produto->getCodigo())) : ?>
                        <h4 class="text-muted">Código: #<?php echo $produto->getCodigo(); ?></h4>
                    <?php endif; ?>
                    <p>
                        <?php if ($produto->getValorPromocao() > 0) : ?>
                            De: <span style="text-decoration:line-through"><small>R$ </small><?php echo "<strong>" . number_format($produto->getValor(), 2, "</strong><small>,", ".") . "</small>"; ?></span>
                            <span class="text-danger">Por: <small>R$ </small><?php echo "<strong>" . number_format($produto->getValorPromocao(), 2, "</strong><small>,", ".") . "</small>"; ?></span>
                        <?php else : ?>
                            Por: <small>R$ </small><?php echo "<strong>" . number_format($produto->getValor(), 2, "</strong><small>,", ".") . "</small>"; ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($produto->getUnidade() > 0) : ?>
                        <p>
                            <strong>Volume:</strong>
                            <?php echo $produto->getVolumeStr(); ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($produto->getDescricao())) : ?>
                        <h5>Descrição</h5>
                        <p><?php echo $produto->getDescricao(); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
