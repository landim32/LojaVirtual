<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var LojaInfo[] $lojas
 * @var string $urlFormato
 */
?>
<?php if (!is_null($lojas) && count($lojas) > 1 && !isNullOrEmpty($urlFormato)) : ?>
    <div class="modal fade" id="mudarLojaModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="fa fa-shopping-cart"></i> Mudar Loja
                    </h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-3">Loja:</label>
                        <div class="col-md-9">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $loja->getNome(); ?> <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php foreach ($lojas as $lojaMuda) : ?>
                                        <li><a href="<?php echo $app->getBaseUrl() . "/loja/" . $lojaMuda->getSlug() . "/mudar?callback=" . urlencode($urlFormato); ?>"><?php echo $lojaMuda->getNome(); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="card hovercard">
    <div class="cardheader"></div>
    <div class="avatar">
        <a href="<?php echo $app->getBaseUrl() . "/loja/" . $loja->getSlug(); ?>">
            <img src="<?php echo $loja->getFotoUrl(100, 100); ?>" class="img-circle" />
        </a>
    </div>
    <div class="info">
        <div class="title">
            <a href="<?php echo $app->getBaseUrl() . "/loja/" . $loja->getSlug(); ?>">
                <?php echo $loja->getNome(); ?>
            </a>
        </div>
        <?php if (!is_null($lojas) && count($lojas) > 1 && !isNullOrEmpty($urlFormato)) : ?>
            <div class="desc">
                <a href="#mudarLojaModal" data-toggle="modal" data-target="#mudarLojaModal">
                    <i class="fa fa-refresh"></i> Trocar Loja
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
