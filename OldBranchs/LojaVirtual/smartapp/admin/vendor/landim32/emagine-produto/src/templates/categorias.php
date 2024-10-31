<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\CategoriaInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var CategoriaInfo[] $categorias
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
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-reorder"></i> Categorias</h3>
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
                            <th><a href="#">Categoria</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($categorias as $categoria) : ?>
                            <?php
                            $urlCategoria = $app->getBaseUrl() . "/" . $loja->getSlug() . "/categoria/" . $categoria->getSlug();
                            $urlExcluir = $app->getBaseUrl() . "/" . $loja->getSlug() . "/categoria/" . $categoria->getSlug() . "/excluir";
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $urlCategoria; ?>">
                                        <span class="badge">
                                            <img src="<?php echo $categoria->getFotoUrl(30, 30); ?>" alt="<?php echo $categoria->getNome(); ?>" />
                                        </span>
                                    </a>
                                </td>
                                <td><a href="<?php echo $urlCategoria; ?>"><?php echo $categoria->getNomeCompleto(); ?></a></td>
                                <td class="text-right">
                                    <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/categoria/nova"; ?>"><i class="fa fa-plus"></i> Nova Categoria</a><br />
        </div>
    </div>
</div>