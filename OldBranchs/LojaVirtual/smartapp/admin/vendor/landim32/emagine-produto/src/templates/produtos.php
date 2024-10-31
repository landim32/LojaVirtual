<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoRetornoInfo;

/**
 * @var EmagineApp $app
 * @var ProdutoRetornoInfo $produtos
 * @var LojaInfo $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $palavraChave
 * @var string $erro
 */

$urlProduto = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos?pg=%s";
$paginacao = admin_pagination($produtos->getQuantidadePagina(), $urlProduto, $produtos->getPagina());
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <!--h3 style="margin-top: 5px; margin-bottom: 5px;">
                <i class="fa fa-shopping-cart"></i> Busca por Produtos
            </h3-->
            <?php if (!isNullOrEmpty($erro)) : ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fa fa-warning"></i> <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6">
                    <form method="get" class="form-horizontal">
                        <input type="hidden" name="pg" value="<?php echo $produtos->getPagina(); ?>" />
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>
                            <input type="text" name="p" class="form-control" placeholder="Busca por produtos" value="<?php echo $palavraChave; ?>" />
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/buscar"; ?>" class="btn btn-lg btn-success">
                        <i class="fa fa-plus"></i> Novo
                    </a>
                </div>
            </div>
            <hr />
            <?php foreach ($produtos->getProdutos() as $produto) : ?>
                <?php
                $urlProduto = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug();
                $urlExcluir = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug() . "/excluir";
                ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <a href="<?php echo $urlProduto; ?>">
                                    <img src="<?php echo $produto->getFotoUrl(90, 90); ?>" class="img-responsive" />
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo $urlProduto; ?>" style="font-weight: bold">
                                    <?php echo $produto->getNome(); ?>
                                </a>
                                <?php if ($produto->getDestaque() == true) : ?>
                                    <label class="label label-info">Destaque</label>
                                <?php endif; ?>
                                <?php
                                switch ($produto->getCodSituacao()) {
                                    case ProdutoInfo::ATIVO:
                                        echo "<span class='label label-success'>Ativo</span>";
                                        break;
                                    case ProdutoInfo::INATIVO:
                                        echo "<span class='label label-danger'>Inativo</span>";
                                        break;
                                }
                                ?>
                                <br />
                                Cód: <a href="<?php echo $urlProduto; ?>">#<?php echo $produto->getCodigo(); ?></a><br />
                                <a href="<?php echo $urlProduto; ?>"><?php echo $produto->getCategoria()->getNome(); ?></a><br />

                            </div>
                            <div class="col-md-3 text-right">
                                <div>
                                    <?php if ($produto->getValorPromocao() > 0) : ?>
                                        <a href="<?php echo $urlProduto; ?>" class="text-danger" style="text-decoration: line-through;">
                                            <span>R$</span>
                                            <strong style="font-size: 140%"><?php echo $produto->getValorStr(); ?></strong>
                                        </a>
                                        <a href="<?php echo $urlProduto; ?>">
                                            <span>R$</span>
                                            <strong style="font-size: 140%"><?php echo $produto->getValorPromocaoStr(); ?></strong>
                                        </a>
                                    <?php else : ?>
                                    <a href="<?php echo $urlProduto; ?>">
                                        <span>R$</span>
                                        <strong style="font-size: 140%"><?php echo $produto->getValorFinalStr(); ?></strong>
                                    </a>
                                    <?php endif; ?>
                                </div>
                                <!--div>
                                    <span>Volume:</span>
                                    <a href="<?php echo $urlProduto; ?>">
                                        <?php echo $produto->getVolumeStr(); ?>
                                    </a>
                                </div-->
                                <?php if ($loja->getControleEstoque() == true) : ?>
                                    <div>
                                        <span>Estoque:</span>
                                        <?php if ($produto->getQuantidade() > $loja->getEstoqueMinimo()): ?>
                                            <a href="<?php echo $urlProduto; ?>">
                                                <?php echo $produto->getQuantidade(); ?>
                                            </a>
                                        <?php else : ?>
                                            <a href="<?php echo $urlProduto; ?>" class="text-danger">
                                                <?php echo $produto->getQuantidade(); ?>
                                                <i class="fa fa-warning"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-1 text-right">
                                <a class="confirm text-danger" href="<?php echo $urlExcluir; ?>" style="font-size: 150%">
                                    <i class="fa fa-remove"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="text-center"><?php echo $paginacao; ?></div>
        </div>
    </div>
</div>