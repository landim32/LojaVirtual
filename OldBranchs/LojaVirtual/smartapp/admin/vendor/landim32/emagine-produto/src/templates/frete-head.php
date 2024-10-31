<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var LojaFreteInfo $frete
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
 */

$urlGravar = $app->getBaseUrl() . "/" . $loja->getSlug() . "/frete/gravar";

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-truck"></i> Valor de Frete</h3>
                </div>
                <div class="panel-body">
                    <?php if (!StringUtils::isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" class="form-horizontal" action="<?php echo $urlGravar ?>">
                        <input type="hidden" name="id_frete" value="<?php echo $frete->getId(); ?>" />