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
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <?php if (!isNullOrEmpty($seguimento->getIcone())) : ?>
                                        <span class="badge">
                                            <img src="<?php echo $seguimento->getIconeUrl(80, 80); ?>" alt="<?php echo $seguimento->getNome(); ?>" />
                                        </span>
                                    <?php else : ?>
                                        <i class="fa fa-balance-scale" style="font-size: 80px;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-9">
                                    <h2><?php echo $seguimento->getNome(); ?></h2>
                                </div>
                            </div>
                            <hr />
                            <dl class="dl-horizontal">
                                <dt>Id:</dt>
                                <dd><?php echo $seguimento->getId(); ?></dd>
                                <dt>Slug:</dt>
                                <dd><?php echo $seguimento->getSlug(); ?></dd>
                                <dt>Nome:</dt>
                                <dd><?php echo $seguimento->getNome(); ?></dd>
                                <dt>&nbsp;</dt>
                                <dd>
                                    <i class="<?php
                                    echo $seguimento->getApenasPJ() ? "fa fa-check-square" : "fa fa-square";
                                    ?>"></i>
                                    Apenas pessoas juridícas podem entrar
                                </dd>
                            </dl>
                            <hr />
                            <div class="text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/seguimentos"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <a href="<?php echo $app->getBaseUrl() . "/seguimento/" . $seguimento->getSlug() . "/alterar"; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
                    <a class="confirm" href="<?php echo $app->getBaseUrl() . "/seguimento/" . $seguimento->getSlug() . "/excluir"; ?>"><i class="fa fa-trash"></i> Excluir</a>
                </div>
            </div>

        </div>
    </div>
</div>