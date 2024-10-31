<?php

namespace Emagine\Loja;

use Emagine\Login\Model\GrupoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var LojaInfo $loja
 * @var GrupoInfo[]|null $grupos
 * @var string[]|null $situacoes
 * @var string @erro
 */
$url = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-user-circle"></i> Minha Conta</li>
        <li class="active"><i class="fa fa-user-circle"></i> Alterar Dados</li>
    </ol>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?php echo $url . "/alterar-meus-dados"; ?>" class="list-group-item active">
                    <i class="fa fa-fw fa-user-circle"></i> Alterar Dados
                </a>
                <a href="<?php echo $url . "/enderecos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-map-marker"></i> Endereços
                </a>
                <a href="<?php echo $url . "/pedidos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-shopping-cart"></i> Pedidos feitos
                </a>
                <a href="<?php echo $url . "/lista-de-desejos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-heart"></i> Lista de Desejos
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-user-circle-o"></i> Alterar meus dados</h3>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal">
                        <input type="hidden" name="id_usuario" value="<?php echo $usuario->getId(); ?>" />
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group form-group-lg">
                            <label class="control-label col-md-3" for="nome">Nome:</label>
                            <div class="col-md-9">
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $usuario->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="control-label col-md-3" for="cpf_cnpj">CPF/CNPJ:</label>
                            <div class="col-md-9">
                                <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" value="<?php echo $usuario->getCpfCnpj(); ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="control-label col-md-3" for="email">Email:</label>
                            <div class="col-md-9">
                                <input type="text" id="email" name="email" class="form-control" value="<?php echo $usuario->getEmail(); ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="control-label col-md-3" for="telefone">Telefone:</label>
                            <div class="col-md-4">
                                <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo $usuario->getTelefone(); ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <!--a href="<?php echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil"; ?>" class="btn btn-lg btn-default"><i class="fa fa-ban"></i> Cancelar</a-->
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
