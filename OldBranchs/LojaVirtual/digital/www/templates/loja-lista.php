<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo[] $lojas
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="text-center">
                <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" alt="<?php echo APP_NAME; ?>" class="img-responsive" style="max-height: 120px; margin: 5px auto;" />
            </div>
            <h3 class="text-center">Selecione a loja onde deseja fazer suas compras:</h3>

            <div class="list-group">
                <?php foreach ($lojas as $loja) : ?>
                    <?php $urlLoja = $app->getBaseUrl() . "/" . $loja->getSlug(); ?>
                    <a href="<?php echo $urlLoja; ?>" class="list-group-item">
                        <h4 class="list-group-item-heading"><?php echo $loja->getNome(); ?></h4>
                        <p class="list-group-item-text">
                            <?php echo $loja->getEnderecoCompleto(); ?>
                        </p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>