<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
/**
 * @var EmagineApp $app;
 * @var LojaInfo $loja
 * @var ProdutoInfo[] $produtos
 * @var string $paginacao
 */
$urlBase = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
    <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($produtos as $produto) : ?>
            <?php $urlProduto = $urlBase . "/" . $produto->getCategoria()->getSlug() . "/" . $produto->getSlug(); ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="thumbnail">
                    <a href="<?php echo $urlProduto; ?>">
                        <img src="<?php echo $produto->getFotoUrl(250, 250); ?>" class="img-responsive" alt="<?php echo $produto->getNome(); ?>" style="width: 250px; height: 250px" />
                    </a>
                    <div class="caption text-center">
                        <h5><a href="<?php echo $urlProduto; ?>"><?php echo $produto->getNome(); ?></a></h5>
                        <p>
                            <?php if ($produto->getValorPromocao() > 0) : ?>
                                De: <span style="text-decoration:line-through"><small>R$ </small><?php echo "<strong>" . number_format($produto->getValor(), 2, "</strong><small>,", ".") . "</small>"; ?></span>
                                <span class="text-danger">Por: <small>R$ </small><?php echo "<strong>" . number_format($produto->getValorPromocao(), 2, "</strong><small>,", ".") . "</small>"; ?></span>
                            <?php else : ?>
                                Por: <small>R$ </small><?php echo "<strong>" . number_format($produto->getValor(), 2, "</strong><small>,", ".") . "</small>"; ?>
                            <?php endif; ?>
                        </p>
                        <div class="btn-adicionar"
                             data-id="<?php echo $produto->getId(); ?>"
                             data-foto="<?php echo $produto->getFoto(); ?>"
                             data-nome="<?php echo $produto->getNome(); ?>"
                             data-valor="<?php echo number_format( $produto->getValor(), 2, ".", ""); ?>"
                             data-promocao="<?php echo number_format( $produto->getValorPromocao(), 2, ".", ""); ?>"
                        ></div>
                    </div>
                </div>
            </div>
            <?php
            $i++;
            if ($i % 4 == 0) {
                echo "</div><div class='row'>";
            }
            ?>
        <?php endforeach; ?>
    </div>
    <?php if (!isNullOrEmpty($paginacao)) : ?>
        <div class="row">
            <div class="col-md-12 text-center"><?php echo $paginacao; ?></div>
        </div>
    <?php endif; ?>
</div>