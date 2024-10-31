<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var ProdutoInfo[] $produtos
 * @var LojaInfo[] $lojas
 * @var LojaInfo $loja
 * #var UsuarioInfo $usuario
 * @var string $erro
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render($app->getBaseUrl() . "/%s/produtos"); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-shopping-cart"></i> Produtos</h3>
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/buscar"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Novo Produto</a>
                        </div>
                    </div>
                    <hr />
                    <?php if (!isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped table-hover table-responsive datatable">
                        <thead>
                        <tr>
                            <th><a href="#">Foto</a></th>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Código</a></th>
                            <th><a href="#">Categoria</a></th>
                            <th class="text-right"><a href="#">Valor</a></th>
                            <th class="text-right"><a href="#">Volume</a></th>
                            <?php if ($loja->getControleEstoque() == true) : ?>
                                <th class="text-right"><a href="#">Quantidade</a></th>
                            <?php endif; ?>
                            <th><a href="#">Situação</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($produtos as $produto) : ?>
                            <?php
                            $urlProduto = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug();
                            $urlExcluir = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug() . "/excluir";
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $urlProduto; ?>">
                                        <img src="<?php echo $app->getBaseUrl() . "/produto/30x30/" . $produto->getFoto(); ?>" class="img-circle" />
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo $urlProduto; ?>">
                                        <?php echo $produto->getNome(); ?>
                                    </a>
                                    <?php if ($produto->getDestaque() == true) : ?>
                                        <label class="label label-info">Destaque</label>
                                    <?php endif; ?>
                                </td>
                                <td><a href="<?php echo $urlProduto; ?>"><?php echo $produto->getCodigo(); ?></a></td>
                                <td><a href="<?php echo $urlProduto; ?>"><?php echo $produto->getCategoria()->getNome(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlProduto; ?>"><?php echo $produto->getValorFinalStr(); ?></a></td>
                                <td class="text-right"><a href="<?php echo $urlProduto; ?>"><?php echo $produto->getVolumeStr(); ?></a></td>
                                <?php if ($loja->getControleEstoque() == true) : ?>
                                    <td class="text-right">
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
                                    </td>
                                <?php endif; ?>
                                <td>
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
                                </td>
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
    </div>
</div>