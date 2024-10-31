<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var CategoriaInfo $categoria
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
                                    <?php if (!StringUtils::isNullOrEmpty($categoria->getFoto())) : ?>
                                        <span class="badge">
                                            <img src="<?php echo $categoria->getFotoUrl(80, 80); ?>" class="img-responsive" alt="<?php echo $categoria->getNome(); ?>" />
                                        </span>
                                    <?php else : ?>
                                        <i class="fa fa-suitcase" style="font-size: 80px;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-9">
                                    <h2><?php echo $categoria->getNome(); ?></h2>
                                </div>
                            </div>
                            <hr />
                            <dl class="dl-horizontal">
                                <dt>Id:</dt>
                                <dd><?php echo $categoria->getId(); ?></dd>
                                <?php if (!is_null($categoria->getPai())) : ?>
                                    <dt>Categoria Pai:</dt>
                                    <dd><?php echo $categoria->getPai()->getNome(); ?></dd>
                                <?php endif; ?>
                                <dt>Slug:</dt>
                                <dd><?php echo $categoria->getSlug(); ?></dd>
                                <?php if (!StringUtils::isNullOrEmpty($categoria->getFoto())) : ?>
                                    <dt>Foto:</dt>
                                    <dd><?php echo $categoria->getFoto(); ?></dd>
                                <?php endif; ?>
                                <dt>Nome:</dt>
                                <dd><?php echo $categoria->getNome(); ?></dd>
                                <dt>Nome Completo:</dt>
                                <dd><?php echo $categoria->getNomeCompleto(); ?></dd>
                            </dl>
                            <hr />
                            <div class="text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/categorias"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/categoria/" . $categoria->getSlug() . "/alterar"; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
                    <a class="confirm" href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug()  . "/categoria/" . $categoria->getSlug() . "/excluir"; ?>"><i class="fa fa-trash"></i> Excluir</a>
                </div>
            </div>

        </div>
    </div>
</div>