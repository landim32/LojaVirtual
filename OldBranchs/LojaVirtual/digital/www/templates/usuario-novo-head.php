<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var string $erro
 */

?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $app->getBaseUrl(); ?>"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><i class="fa fa-user-circle"></i> Cadastro</li>
        <?php if (!is_null($usuario)) : ?>
            <li><a href="<?php echo $url . "/carrinho"; ?>"><i class="fa fa-shopping-cart"></i> Finalizar Pedido</a></li>
        <?php else : ?>
            <li><i class="fa fa-shopping-cart"></i> Finalizar Pedido</li>
        <?php endif; ?>
    </ol>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="post" class="form-horizontal">
                        <?php if ($usuario->getId() > 0) : ?>
                            <input type="hidden" name="id_usuario" value="<?php echo $usuario->getId(); ?>" />
                        <?php endif; ?>
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group form-group-lg">
                                    <label class="control-label col-md-3" for="nome">Nome(*):</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                                            <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $usuario->getNome(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="control-label col-md-3" for="cpf_cnpj">CPF/CNPJ:</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-fw fa-id-card"></i></span>
                                            <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" value="<?php echo $usuario->getCpfCnpj(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="control-label col-md-3" for="email">Email(*):</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                                            <input type="text" id="email" name="email" class="form-control" value="<?php echo $usuario->getEmail(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="control-label col-md-3" for="telefone">Telefone:</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-fw fa-phone"></i></span>
                                            <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo $usuario->getTelefone(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <?php if (!($usuario->getId() > 0)) : ?>
                                    <div class="form-group form-group-lg">
                                        <label class="control-label col-md-3" for="senha">Senha:</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                                <input type="password" id="senha" name="senha" class="form-control" placeholder="Preencha a senha" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="control-label col-md-3" for="senha">Confirma:</label>
                                        <div class="col-md-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                                <input type="password" name="confirma" class="form-control" placeholder="Confirme sua senha" />
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-7">
