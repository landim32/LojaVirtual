<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo[] $lojas
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
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-bag"></i> Lojas</h3>
                    <hr />
                    <?php if (!StringUtils::isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-hover table-responsive datatable">
                        <thead>
                        <tr>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Endereço</a></th>
                            <th><a href="#">Situação</a></th>
                            <th class="text-right"><a href="#">Quant.</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lojas as $loja) : ?>
                            <?php
                            $urlLoja = $app->getBaseUrl() . "/loja/" . $loja->getSlug();
                            $urlProduto = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos";
                            $urlCategoria = $app->getBaseUrl() . "/" . $loja->getSlug() . "/categorias";
                            $urlUnidade = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades";
                            $urlHorario = $app->getBaseUrl() . "/" . $loja->getSlug() . "/horarios";
                            $urlExcluir = $app->getBaseUrl() . "/loja/" . $loja->getSlug() . "/excluir";
                            ?>
                            <tr>
                                <td><a href="<?php echo $urlLoja; ?>"><?php echo $loja->getNome(); ?></a></td>
                                <td><a href="<?php echo $urlLoja; ?>"><?php echo $loja->getEnderecoCompleto(); ?></a></td>
                                <td>
                                    <a href="<?php echo $urlLoja; ?>" class="<?php echo $loja->getSituacaoClasse(); ?>">
                                        <?php echo $loja->getSituacaoStr(); ?>
                                    </a>
                                </td>
                                <td class="text-right">
                                    <a href="<?php echo $urlLoja; ?>"><?php echo $loja->getQuantidade(); ?></a>
                                </td>
                                <td class="text-right" nowrap>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-cog"></i> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="<?php echo $urlProduto; ?>">
                                                    <span class="badge pull-right"><?php echo $loja->getQuantidade(); ?></span>
                                                    <i class="fa fa-fw fa-shopping-cart"></i> Produtos
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $urlCategoria; ?>">
                                                    <i class="fa fa-fw fa-reorder"></i> Categorias
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $urlUnidade; ?>">
                                                    <i class="fa fa-fw fa-balance-scale"></i> Unidades
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $urlHorario; ?>">
                                                    <i class="fa fa-fw fa-clock-o"></i> Horários
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                                    <i class="fa fa-fw fa-remove"></i> Excluir
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-md-2" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/loja/nova"; ?>"><i class="fa fa-plus"></i> Nova Loja</a>
        </div>
    </div>
</div>