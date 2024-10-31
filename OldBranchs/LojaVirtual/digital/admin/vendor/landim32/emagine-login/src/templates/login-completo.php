<?php
namespace Emagine\Login;
/**
 * @var string $error
 * @var string $urlCadastro
 * @var string $urlResetar
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <form class="form-vertical" method="POST">
                <div class="panel panel-default" style="min-height: 350px;">
                    <div class="panel-body">
                        <div class="form-group text-center">
                            <i class="fa fa-user-circle fa-5x"></i>
                        </div>
                        <h3 class="text-center">JÁ SOU CADASTRADO</h3>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Email" autofocus="autofocus" value="<?php //echo $usuario; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input type="password" name="senha" class="form-control" placeholder="Senha" />
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">LOGIN</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <form class="form-vertical" method="POST">
                <div class="panel panel-default" style="min-height: 350px;">
                    <div class="panel-body text-center">
                        <div class="form-group text-center">
                            <i class="fa fa-user-plus fa-5x"></i>
                        </div>
                        <h3>SOU UM NOVO CLIENTE</h3>
                        <div style="min-height: 122px">
                            <br />
                            <p>Preencha os dados do formulário necessário para criar sua conta e finalize o pedido.</p>
                            <br />
                        </div>
                        <div class="form-group">
                            <a href="<?php echo $urlCadastro; ?>" class="btn btn-lg btn-block btn-primary">CRIAR CONTA <i class="fa fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center">
        Esqueceu sua senha? <a href="<?php echo $urlResetar; ?>"><strong>Clique aqui</strong></a> para recupara-la.<br>
    </div>
</div>
