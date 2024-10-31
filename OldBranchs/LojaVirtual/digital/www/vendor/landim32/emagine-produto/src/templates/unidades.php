<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\UnidadeInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UnidadeInfo[] $unidades
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
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
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-balance-scale"></i> Unidades de medida</h3>
                    <hr />
                    <?php if (!isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Unidades</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($unidades) > 0) : ?>
                        <?php foreach ($unidades as $unidade) : ?>
                            <?php
                            $urlUnidade = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidade/" . $unidade->getSlug();
                            $urlExcluir = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidade/" . $unidade->getSlug() . "/excluir";
                            ?>
                            <tr>
                                <td><a href="<?php echo $urlUnidade; ?>"><?php echo $unidade->getNome(); ?></a></td>
                                <td class="text-right">
                                    <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="2">
                                    <i class="fa fa-warning"></i> Nenhuma unidade de medida cadastrada!
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidade/nova"; ?>"><i class="fa fa-plus"></i> Nova Unidade</a><br />
        </div>
    </div>
</div>