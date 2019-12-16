<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;

/**
 * @var EmagineApp $app
 * @var string $urlCadastro
 * @var string $urlResetar
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
    <title>Login</title>
    <?php echo $app->renderCss(); ?>
</head>
<body class="login">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-vertical" method="POST">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group text-center foto">
                            <i class="fa fa-user-circle"></i>
                        </div>
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

            <div class="text-center">
                Esqueceu sua senha? <a href="<?php echo $urlResetar; ?>"><strong>Clique aqui</strong></a> para recupara-la.<br>
                Ainda não tem conta? <a href="<?php echo $urlCadastro; ?>"><strong>Cadastre-se, é grátis</strong></a>!
            </div>
        </div>
    </div>
</div>
<?php echo $app->renderJavascript(); ?>
</body>
</html>
