<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;

/**
 * @var EmagineApp $app
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
<body class="resetar-senha">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-vertical" method="POST">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h1><i class="fa fa-lock"></i> Resetar senha</h1>
                        <p style="margin: 20px auto;">
                            Uma mensagem será enviada para o seu email com o link para resetar sua senha.
                        </p>
                        <div class="form-group">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Preencha seu email" autofocus="autofocus" />
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-block btn-primary">RESETAR SENHA</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="text-center">
                Ainda não tem conta? <a href="<?php echo $app->getBaseUrl() . "/cadastro"; ?>"><strong>Cadastre-se, é grátis</strong></a>!<br />
                Eu já tenho uma conta e desejo <a href="<?php echo $app->getBaseUrl() . "/login"; ?>" style="font-weight: bold;">entrar</a>.
            </div>
        </div>
    </div>
</div>
<?php echo $app->renderJavascript(); ?>
</body>
</html>
