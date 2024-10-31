<?php

namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\GrupoBLL;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string @erro
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
                    <h3 class="panel-title">
                        <?php if ($usuario->getId() > 0) : ?>
                            <i class="fa fa-user-circle-o"></i> Alterar dados do Usuário
                        <?php else : ?>
                            <i class="fa fa-user-circle-o"></i> Novo usuário
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal">
                        <?php echo require __DIR__ . "/usuario-form.php"; ?>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="<?php
                                if ($usuario->getId() > 0) {
                                    echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
                                }
                                else {
                                    echo $app->getBaseUrl() . "/usuario/listar";
                                }
                                ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if ($usuario->getId() > 0) : ?>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/trocar-senha"; ?>"><i class="fa fa-fw fa-lock"></i> Trocar senha</a><br />
            <a href="<?php echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil"; ?>"><i class="fa fa-fw fa-search"></i> Ver Perfil</a><br />
        </div>
        <?php endif; ?>
    </div>
</div>
