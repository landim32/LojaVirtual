<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
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
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <i class="fa fa-balance-scale" style="font-size: 80px;"></i>
                                </div>
                                <div class="col-md-9">
                                    <h2><?php echo $unidade->getNome(); ?></h2>
                                </div>
                            </div>
                            <hr />
                            <dl class="dl-horizontal">
                                <dt>Id:</dt>
                                <dd><?php echo $unidade->getId(); ?></dd>
                                <dt>Slug:</dt>
                                <dd><?php echo $unidade->getSlug(); ?></dd>
                                <dt>Nome:</dt>
                                <dd><?php echo $unidade->getNome(); ?></dd>
                            </dl>
                            <hr />
                            <div class="text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidade/" . $unidade->getSlug() . "/alterar"; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
                    <a class="confirm" href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug()  . "/unidade/" . $unidade->getSlug() . "/excluir"; ?>"><i class="fa fa-trash"></i> Excluir</a>
                </div>
            </div>

        </div>
    </div>
</div>