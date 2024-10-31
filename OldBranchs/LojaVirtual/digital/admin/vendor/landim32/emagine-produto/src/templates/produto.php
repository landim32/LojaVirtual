<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var ProdutoInfo $produto
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
                                    <?php if (!StringUtils::isNullOrEmpty($produto->getFoto())) : ?>
                                        <img src="<?php echo $app->getBaseUrl() . "/produto/120x80/" . $produto->getFoto(); ?>" class="img-responsive" />
                                    <?php else : ?>
                                        <i class="fa fa-suitcase" style="font-size: 80px;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-9">
                                    <h2><?php echo $produto->getNome() . " #" . $produto->getId(); ?></h2>
                                    <span class="badge"><?php echo $produto->getSituacaoStr(); ?></span>
                                    <?php if ($produto->getDestaque() == true) : ?>
                                        <span class="badge">Em destaque</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr />
                            <?php if ($produto->getQuantidade() <= $loja->getEstoqueMinimo()): ?>
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <i class="fa fa-warning"></i> Estoque abaixo de <?php echo $loja->getEstoqueMinimo(); ?>.
                                </div>
                            <?php endif; ?>
                            <dl class="dl-horizontal">
                                <?php if (!isNullOrEmpty($produto->getCodigo())) : ?>
                                <dt>Código:</dt>
                                <dd><?php echo $produto->getCodigo(); ?></dd>
                                <?php endif; ?>
                                <dt>Usuário:</dt>
                                <dd><?php echo $produto->getUsuario()->getNome(); ?></dd>
                                <dt>Categoria:</dt>
                                <dd><?php echo $produto->getCategoria()->getNomeCompleto(); ?></dd>
                                <dt>Slug:</dt>
                                <dd><?php echo $produto->getSlug(); ?></dd>
                                <dt>Valor:</dt>
                                <dd><?php echo $produto->getValorStr(); ?></dd>
                                <?php if ($produto->getValorPromocao() > 0) : ?>
                                <dt>Valor em Promoção:</dt>
                                <dd><?php echo $produto->getValorPromocaoStr(); ?></dd>
                                <?php endif; ?>
                                <!--dt>Volume:</dt>
                                <dd><?php echo $produto->getVolumeStr(); ?></dd-->
                                <?php if ($loja->getControleEstoque() == true) : ?>
                                <dt>Quantidade:</dt>
                                <dd>
                                    <?php if ($produto->getQuantidade() > $loja->getEstoqueMinimo()): ?>
                                        <?php echo $produto->getQuantidade(); ?>
                                    <?php else : ?>
                                        <span class="text-danger">
                                            <?php echo $produto->getQuantidade(); ?> <i class="fa fa-warning"></i>
                                        </span>
                                    <?php endif; ?>
                                </dd>
                                <?php endif; ?>
                                <?php if ($produto->getQuantidadeVendido() > 0) : ?>
                                <dt>Quantidade Vendidos:</dt>
                                <dd><?php echo $produto->getQuantidadeVendido(); ?></dd>
                                <?php endif; ?>
                                <?php if (!isNullOrEmpty($produto->getDescricao())) : ?>
                                    <dt>Descrição:</dt>
                                    <dd><?php echo $produto->getDescricao(); ?></dd>
                                <?php endif; ?>
                            </dl>
                            <hr />
                            <div class="text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug() . "/alterar"; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
                    <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/novor"; ?>"><i class="fa fa-plus"></i> Novo</a><br />
                    <a class="confirm" href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug() . "/excluir"; ?>"><i class="fa fa-trash"></i> Excluir</a>
                </div>
            </div>

        </div>
    </div>
</div>