<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 */
?>
<div class="card hovercard">
    <div class="cardheader">

    </div>
    <div class="avatar">
        <a href="#" id="foto-usuario">
            <?php if (UsuarioBLL::usaFoto()) : ?>
            <img src="<?php echo $usuario->getThumbnailUrl(100, 100); ?>" alt="">
            <?php else : ?>
                <i class="fa fa-5x fa-user-circle-o" style="color: #fff; text-shadow: 0px 1px 1px #000"></i>
            <?php endif; ?>
        </a>
    </div>
    <div class="info">
        <div class="title">
            <a href="<?php echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil"; ?>"><?php echo $usuario->getNome(); ?></a>
        </div>
        <div class="desc"><?php echo $usuario->getGrupoStr(); ?></div>
    </div>
    <!--div class="bottom">
        <a class="btn btn-primary btn-twitter btn-sm" href="https://twitter.com/webmaniac">
            <i class="fa fa-twitter"></i>
        </a>
        <a class="btn btn-danger btn-sm" rel="publisher"
           href="https://plus.google.com/+ahmshahnuralam">
            <i class="fa fa-google-plus"></i>
        </a>
        <a class="btn btn-primary btn-sm" rel="publisher"
           href="https://plus.google.com/shahnuralam">
            <i class="fa fa-facebook"></i>
        </a>
        <a class="btn btn-warning btn-sm" rel="publisher" href="https://plus.google.com/shahnuralam">
            <i class="fa fa-behance"></i>
        </a>
    </div-->
</div>