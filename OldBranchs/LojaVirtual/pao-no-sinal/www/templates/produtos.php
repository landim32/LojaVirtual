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
?>
<div class="container">
    <div class="row">
        <?php foreach ($produtos as $produto) : ?>
            <div class="col-sm-4 col-md-3">
                <div class="thumbnail">
                    <img src="<?php echo $app->getBaseUrl() . "/produto/250x250/" . $produto->getFoto(); ?>" class="img-responsive" alt="<?php echo $produto->getNome(); ?>" />
                    <div class="caption text-center">
                        <h5><?php echo $produto->getNome(); ?></h5>
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
        <?php endforeach; ?>
    </div>
    <?php if (!isNullOrEmpty($paginacao)) : ?>
    <div class="row">
        <div class="col-md-12 text-center"><?php echo $paginacao; ?></div>
    </div>
    <?php endif; ?>
</div>
