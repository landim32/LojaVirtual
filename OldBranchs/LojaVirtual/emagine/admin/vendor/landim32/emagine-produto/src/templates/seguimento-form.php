<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\SeguimentoInfo;

/**
 * @var EmagineApp $app
 * @var SeguimentoInfo $seguimento
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
                    <h3 class="panel-title"><i class="fa fa-balance-scale"></i> Seguimentos</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/seguimento"; ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <input type="hidden" name="id_seguimento" value="<?php echo $seguimento->getId(); ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="icone">Icone:</label>
                            <div class="col-md-9">
                                <input type="file" id="icone" name="icone" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="nome">Nome:</label>
                            <div class="col-md-9">
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $seguimento->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-9 col-lg-offset-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="apenas_pj"
                                           value="1"<?php echo $seguimento->getApenasPJ() ? " checked='checked'" : ""; ?> />
                                        Apenas pessoas jurídicas podem entrar
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/seguimentos"; ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>