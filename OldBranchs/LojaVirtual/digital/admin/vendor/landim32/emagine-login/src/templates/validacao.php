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
    <title>Valide sua conta</title>
    <?php echo $app->renderCss(); ?>
</head>
<body class="validacao">
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <h1>Verifique a sua conta</h1>
                    <p style="margin: 40px auto;">
                        Uma email foi enviado para <strong><?php echo $usuario->getEmail(); ?></strong> solicitando que você valide a sua conta.
                        Clique no link, valide sua conta e retorne.
                    </p>
                    <a href="<?php echo $app->getBaseUrl() . "/login"; ?>" class="btn btn-lg btn-block btn-primary">Entrar com sua conta</a>
                </div>
            </div>

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
