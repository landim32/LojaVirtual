<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var string $urlFormato
 */
?>
<div class="card hovercard">
    <div class="cardheader"></div>
    <div class="avatar">
        <a href="<?php echo $app->getBaseUrl(); ?>">
            <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" class="img-circle" alt="" />
        </a>
    </div>
    <div class="info">
        <div class="title">
            <a href="<?php echo $app->getBaseUrl(); ?>">
                <?php echo APP_NAME; ?>
            </a>
        </div>
    </div>
</div>
