<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Produto\BLL\ProdutoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\UnidadeInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var ProdutoInfo $produto
 * @var CategoriaInfo[] $categorias
 * @var UsuarioInfo[] $usuarios
 * @var UnidadeInfo[] $unidades
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
 * @var string $urlVoltar
 */

$urlVoltar = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos";
$urlNovo = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/novo"

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i> Buscar produtos por palavra-chave</h3>
                </div>
                <div class="panel-body">
                    <?php if (!isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/buscar"; ?>" class="form-horizontal">
                        <input type="hidden" id="id_produto" name="id_origem" value="<?php echo $produto->getIdOrigem(); ?>" />
                        <div class="form-group">
                            <!--label class="col-md-2 control-label" for="nome">Nome:</label-->
                            <div class="col-md-12">
                                <div class="input-group input-group-lg">
                                    <input type="text" id="nome_produto" name="nome_produto" class="form-control produto-original" value="<?php echo $produto->getNome(); ?>" placeholder="Busque por produtos..." />
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Valor <span class="required">*</span>:</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-addon">R$</span>
                                    <input type="text" name="valor" class="form-control money" value="<?php echo number_format($produto->getValor(), 2, ",", "."); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Promoção:</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-addon">R$</span>
                                    <input type="text" name="valor_promocao" class="form-control money" value="<?php echo number_format($produto->getValorPromocao(), 2, ",", "."); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="id_categoria">Categoria <span class="required">*</span>:</label>
                            <div class="col-md-9">
                                <select id="id_categoria" name="id_categoria" class="form-control">>
                                    <?php foreach ($categorias as $categoria) : ?>
                                        <option value="<?php echo $categoria->getId(); ?>"<?php
                                        echo ($categoria->getId() == $produto->getIdCategoria()) ? " selected='selected'" : "";
                                        ?>><?php echo $categoria->getNomeCompleto(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if ($loja->getControleEstoque() == true) : ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="quantidade">Estoque:</label>
                                <div class="col-md-4">
                                    <input type="number" id="quantidade" name="quantidade" class="form-control" value="<?php echo $produto->getQuantidade(); ?>" />
                                </div>
                                <!--div class="col-md-5 text-right">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="destaque" value="1"<?php echo ($produto->getDestaque()) ? ' checked="checked"' : ''; ?> />
                                        Destaque
                                    </label>
                                </div-->
                            </div>
                        <?php else : ?>
                            <!--div class="form-group">
                                <div class="col-md-12 text-right">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="destaque" value="1"<?php echo ($produto->getDestaque()) ? ' checked="checked"' : ''; ?> />
                                        Destaque
                                    </label>
                                </div>
                            </div-->
                        <?php endif; ?>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $urlVoltar; ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $urlNovo; ?>"><i class="fa fa-plus"></i> Não encontrei! Incluir</a><br />
        </div>
    </div>
</div>