<?php

namespace Emagine\Loja;

use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
/**
 * @var LojaInfo $loja
 * @var ProdutoInfo[] $produtos
 */
?>
<div class="container">
    <?php require __DIR__ . "/banner.php"; ?>
</div>
<?php require "produtos.php";
