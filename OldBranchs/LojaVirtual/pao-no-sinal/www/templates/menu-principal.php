<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 */

$regraCategoria = new CategoriaBLL();
$categorias = $regraCategoria->listarPai($loja->getId());
$url = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/%s";
$menuCategoria = $regraCategoria->gerarMenu($url, $categorias);

?>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Alterar navegação</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $app->getBaseUrl() . "/site/" . $loja->getSlug(); ?>">
                <i class="fa fa-home"></i>
            </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <?php echo $menuCategoria; ?>
            <?php //echo $app->getMenu("right"); ?>
        </div><!--/.nav-collapse -->
    </nav>