<?php

namespace Emagine\Loja;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var UsuarioInfo $usuario
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var string $error
 * @var string $urlVoltar
 * @var string $textoVoltar
 * @var string $textoGravar
 * @var bool $fonteGrande
 */
$regraUsuario = new UsuarioBLL();
$url = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><i class="fa fa-user-circle"></i> Cadastro</li>
        <?php if ($regraUsuario->estaLogado()) : ?>
            <li><a href="<?php echo $url . "/carrinho"; ?>"><i class="fa fa-shopping-cart"></i> Finalizar Pedido</a></li>
        <?php else : ?>
            <li><i class="fa fa-shopping-cart"></i> Finalizar Pedido</li>
        <?php endif; ?>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php require "usuario-novo-foot.php"; ?>
                </div>
            </div>
        </div>
    </div>
</div>
