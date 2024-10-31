<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 */

?>
<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Rodrigo Landim">
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo get_tema_path() . "/favicon.ico"; ?>">
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo get_tema_path() . "/favicon.ico"; ?>">
    <title>Cadastro</title>
    <?php echo $app->renderCss(); ?>
</head>
<body class="cadastro">
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form class="form-vertical" method="POST">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Preencha seu email"
                                       value="<?php echo $usuario->getEmail(); ?>" autofocus="autofocus" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                                <input type="text" name="nome" class="form-control" placeholder="Preencha seu nome"
                                       value="<?php echo $usuario->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input type="password" name="senha" class="form-control" placeholder="Preencha a Senha" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                                <input type="password" name="confirma" class="form-control" placeholder="Confirme sua senha" />
                            </div>
                        </div>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">CRIAR CONTA</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="text-center">
                Esqueceu sua senha? <a href="<?php echo $app->getBaseUrl() . "/resetar-senha"; ?>"><strong>Clique aqui</strong></a> para recupara-la.<br>
                Eu já tenho uma conta e desejo <a href="<?php echo $app->getBaseUrl() . "/login"; ?>" style="font-weight: bold;">entrar</a>.
            </div>
        </div>
    </div>
</div>
<?php echo $app->renderJavascript(); ?>
</body>
</html>
