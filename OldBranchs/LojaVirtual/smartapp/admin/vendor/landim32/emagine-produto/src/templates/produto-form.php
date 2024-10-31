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
 * @var string $sucesso
 * @var string $urlVoltar
 */

$regraProduto = new ProdutoBLL();

$urlBusca = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/buscar"

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render() ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-plus"></i> Novo Produto</h3>
                </div>
                <div class="panel-body">
                    <?php if (!isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php elseif (!isNullOrEmpty($sucesso)) : ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-question-circle"></i> <?php echo $sucesso; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto"; ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="id_produto" value="<?php echo $produto->getId(); ?>">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <div class="form-group">
                            <label class="control-label col-md-3">Foto:</label>
                            <div class="col-md-9">
                                <input type="file" name="foto" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="nome">Nome <span class="required">*</span>:</label>
                            <div class="col-md-9">
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $produto->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="id_categoria">Categoria <span class="required">*</span>:</label>
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
                        <div class="form-group">
                            <label class="control-label col-md-3" for="codigo">Código:</label>
                            <div class="col-md-9">
                                <input type="text" id="codigo" name="codigo" class="form-control" value="<?php echo $produto->getCodigo(); ?>" />
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
                        <!--div class="form-group">
                            <label class="col-md-3 control-label" for="volume">Volume:</label>
                            <div class="col-md-3">
                                <input type="text" id="volume" name="volume" class="form-control money" value="<?php
                                echo number_format($produto->getVolume(), 2, ",", ".");
                                ?>" />
                            </div>
                            <label class="col-md-2 control-label" for="id_unidade">Unidade:</label>
                            <div class="col-md-4">
                                <select id="id_unidade" name="id_unidade" class="form-control">
                                    <option value="">--Nenhum--</option>
                                    <?php foreach ($unidades as $unidade) : ?>
                                        <option value="<?php echo $unidade->getId(); ?>"<?php
                                        echo ($unidade->getId() == $produto->getIdUnidade()) ? " selected='selected'" : "";
                                        ?>><?php echo $unidade->getNome(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div-->
                        <!--div class="form-group">
                            <label class="col-md-3 control-label" for="id_usuario">Usuário <span class="required">*</span>:</label>
                            <div class="col-md-9">
                                <select id="id_usuario" name="id_usuario" class="form-control">
                                    <?php /* foreach ($usuarios as $usuario) : ?>
                                        <option value="<?php echo $usuario->getId(); ?>"<?php
                                        echo ($usuario->getId() == $produto->getIdUsuario()) ? " selected='selected'" : "";
                                        ?>><?php echo $usuario->getNome(); ?></option>
                                    <?php endforeach; */ ?>
                                </select>
                            </div>
                        </div-->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="cod_situacao">Situação <span class="required">*</span>:</label>
                            <div class="col-md-9">
                                <select id="cod_situacao" name="cod_situacao" class="form-control">
                                    <?php $lista = $regraProduto->listarSituacao(); ?>
                                    <?php foreach ($lista as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>"<?php
                                        echo ($key == $produto->getCodSituacao()) ? " selected='selected'" : "";
                                        ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!--div class="form-group">
                            <label class="control-label col-md-3" for="descricao">Descrição:</label>
                            <div class="col-md-9">
                                <textarea id="descricao" name="descricao" class="form-control" rows="5"><?php echo $produto->getDescricao(); ?></textarea>
                            </div>
                        </div-->
                        <?php if ($loja->getControleEstoque() == true) : ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="quantidade">Quantidade:</label>
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
                                <button type="submit" class="btn btn-lg btn-success" name="acao" value="gravar-e-adicionar">
                                    <i class="fa fa-plus"></i> Gravar & Adicionar
                                </button>
                                <button type="submit" class="btn btn-lg btn-primary" name="acao" value="gravar">
                                    <i class="fa fa-check"></i> Gravar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $urlBusca; ?>"><i class="fa fa-plus"></i> Buscar Produto</a><br />
        </div>
    </div>
</div>