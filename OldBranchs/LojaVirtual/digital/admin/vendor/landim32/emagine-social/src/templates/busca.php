<?php

namespace Emagine\Social;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Social\BLL\MensagemBLL;
use Emagine\Social\BLL\UsuarioSocialBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Social\Factory\MensagemFactory;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo[] $usuariosResultado
 * @var string $paginacao
 */

$regraUsuario = new UsuarioBLL();
$regraMensagem = MensagemFactory::create();
$regraSocial = new UsuarioSocialBLL();


//$app = $app;
//$usuariosResultado = $usuariosResultado;
//var_dump($usuariosResultado);
?>
<div class="row">
    <div class="col-md-12">
        <form method="GET" class="no-validate">
        <div class="input-group input-group-lg">
            <input type="search" name="p" class="form-control large" placeholder="Busca por parceiros..." value="<?php echo $_GET['p']; ?>">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </span>
        </div>
        </form>
    </div>
</div>
<div class="panel panel-default" style="margin-top: 10px;">
    <div class="panel-body">
        <div class="row">
            <?php $i = 0; ?>
            <?php
                $i = 0;
                /** @var UsuarioInfo $usuario */
                foreach ($usuariosResultado as $usuario) :
             ?>
                <div class="col-md-3">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <img src="<?php echo $usuario->getFoto(); ?>" class="img-circle" style="width: 60px; height: 60px;" />
                        </div>
                        <div class="col-md-8">
                            <h5><b><a href="<?php echo $app->getBaseUrl() . "/perfil/" . $usuario->getSlug(); ?>"><?php echo $usuario->getNome(); ?></a></b></h5>
                            <small><?php $regraMensagem->botaoMensagem($usuario); ?></small><br />
                            <p><small>
                                    <?php /*if (!isNullOrEmpty($usuario->getCreci())) : ?>
                                    <?php echo _('Creci'); ?>: <b><?php echo $usuario->getCreci(); ?></b><br />
                                    <?php endif;*/ ?>
                                    <?php if (!isNullOrEmpty($usuario->getUf())) : ?>
                                        <?php echo "Atua em"; ?> <b><?php echo $usuario->getUf(); ?></b>,
                                    <?php endif; ?>
                                    <!--b><?php echo $usuario->getNome(); ?></b--><br />
                                    <?php if ($usuario->amigos > 0) : ?>
                                        <?php echo ($usuario->amigos > 1) ? "<b>$usuario->amigos</b> "._('friends') : "<b>1</b> "._('friend'); ?>
                                    <?php endif; ?>
                                    <?php if ($usuario->imovel_parceria > 0) : ?>
                                        <?php if ($usuario->amigos > 0) echo ", "; ?>
                                        <?php echo ($usuario->imovel_parceria > 1) ? "<b>$usuario->imovel_parceria</b> "._('properties') : "<b>1</b> "._('property'); ?><br />
                                    <?php endif; ?>
                                </small></p>
                        </div>
                    </div>
                </div>
                <?php $i++; if ($i % 4 == 0) echo '<div class="clearfix"></div>'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <?php echo $paginacao; ?>
    </div>
</div>