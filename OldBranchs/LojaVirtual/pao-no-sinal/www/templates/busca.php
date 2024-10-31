<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var ProdutoInfo[] $produtos
 */
$url = $app->getBaseUrl() . "/site/" . $loja->getSlug();
?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active"><i class="fa fa-search"></i> Resultado da Busca</li>
        </ol>
    </div>
<?php require "produtos.php";
