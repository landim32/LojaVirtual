<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\UnidadeInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UnidadeInfo $unidade
 * @var UsuarioPerfilBLL $usuarioPerfil
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-balance-scale"></i> Unidades</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidade"; ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <input type="hidden" name="id_unidade" value="<?php echo $unidade->getId(); ?>" />
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label">Nome:</label>
                            <div class="col-md-9">
                                <input type="text" name="nome" class="form-control" value="<?php echo $unidade->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades"; ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>