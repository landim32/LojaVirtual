<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\SeguimentoInfo;

/**
 * @var EmagineApp $app
 * @var SeguimentoInfo[] $seguimentos
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
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-building"></i> Seguimentos</h3>
                    <hr />
                    <?php if (!StringUtils::isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Imagem</a></th>
                            <th><a href="#">Seguimento</a></th>
                            <th class="text-center"><a href="#">Apenas PJ</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($seguimentos) > 0) : ?>
                        <?php foreach ($seguimentos as $seguimento) : ?>
                            <?php
                            $urlSeguimento = $app->getBaseUrl() . "/seguimento/" . $seguimento->getSlug();
                            $urlExcluir = $app->getBaseUrl() . "/seguimento/" . $seguimento->getSlug() . "/excluir";
                            ?>
                            <tr>
                                <td>
                                    <span class="badge">
                                        <img src="<?php echo $seguimento->getIconeUrl(20, 20); ?>" alt="<?php echo $seguimento->getNome(); ?>" />
                                    </span>
                                </td>
                                <td><a href="<?php echo $urlSeguimento; ?>"><?php echo $seguimento->getNome(); ?></a></td>
                                <td class="text-center">
                                    <a href="<?php echo $urlSeguimento; ?>">
                                        <i class="<?php echo $seguimento->getApenasPJ() ? "fa fa-check-square" : "fa fa-square"; ?>"></i>
                                    </a>
                                </td>
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
                                    <i class="fa fa-warning"></i> Nenhuma seguimento cadastrada!
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/seguimento/nova"; ?>"><i class="fa fa-plus"></i> Novo Seguimento</a><br />
        </div>
    </div>
</div>