<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\BLL\CategoriaBLL;
/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var CategoriaInfo $categoria
 * @var ProdutoInfo[] $produtos
 */
$regraCategoria = new CategoriaBLL();
$urlHome = $app->getBaseUrl() . "/" . $loja->getSlug();
$urlCategoria = $urlHome . "/%s";
$breadcrumb = $regraCategoria->gerarBreadcrumb($categoria, $urlHome, $urlCategoria);
?>
    <div class="container">
        <?php require __DIR__ . "/banner.php"; ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12"><?php echo $breadcrumb; ?></div>
        </div>
    </div>
<?php require "produtos.php";
