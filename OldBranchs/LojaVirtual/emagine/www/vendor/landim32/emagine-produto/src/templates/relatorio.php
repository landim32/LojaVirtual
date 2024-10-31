<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Report\ReportControl;

/**
 * @var EmagineApp $app
 * @var ProdutoInfo[] $produtos
 * @var LojaInfo|null $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var ReportControl $report
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><?php echo $report->getTitle(); ?></h3>
                    <?php echo $report->execute(); ?>
                </div>
            </div>
        </div>
    </div>
</div>