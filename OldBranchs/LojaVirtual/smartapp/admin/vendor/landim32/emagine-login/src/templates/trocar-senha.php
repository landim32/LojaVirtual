<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Model\UsuarioInfo;
/**
 * @var EmagineApp $app
 * @var bool $usaFoto
 * @var string $erro
 * @var UsuarioInfo $usuario
 */

$urlPerfil = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";

?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="text-center" style="margin: 10px auto 20px;">
                        <?php if ($usaFoto) : ?>
                            <img src="<?php echo $usuario->getThumbnailUrl(100, 100); ?>" class="img-circle" />
                        <?php else : ?>
                            <i class="fa fa-5x fa-user-circle"></i>
                        <?php endif; ?>
                    </div>
                    <h2 class="text-center" style="margin-bottom: 20px;"><?php echo $usuario->getNome(); ?></h2>
                    <?php if (isset($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" class="form-vertical">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input type="password" name="senha" class="form-control" placeholder="Senha" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input type="password" name="confirma" class="form-control" placeholder="Confirmar senha" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a class="btn btn-lg btn-default" href="<?php echo $urlPerfil; ?>">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary">
                                    Trocar Senha <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
