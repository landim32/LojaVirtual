<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Login\Model\UsuarioInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UsuarioInfo $usuario
 * @var string $palavraChave
 */
$regraUsuario = new UsuarioBLL();
if ($regraUsuario->estaLogado()) {
    $urlCarrinho = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/carrinho";
}
else {
    $urlCarrinho = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
}
$urlLogin = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/login";
$urlCadastro = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/cadastro";
$urlAlterar = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/alterar-meus-dados";
$urlLogoff = $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/logoff";
?>
<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Rodrigo Landim">
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo get_tema_path(); ?>/favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo get_tema_path(); ?>/favicon.ico">
    <title><?php echo APP_NAME; ?></title>
    <?php echo $app->renderCss(); ?>
</head>
<body>
<?php echo $app->renderModal(); ?>
<header>
    <div class="container">
        <div class="row">
            <div class="col-md-2 hidden-xs hidden-sm">
                <a href="<?php echo $app->getBaseUrl() . "/"; ?>">
                    <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" class="img-responsive" style="max-height: 120px;" />
                </a>
            </div>
            <div class="col-md-10">
                <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
                    <div id="usuario" class="col-md-3 col-sm-6 col-xs-6">
                        <?php if (!is_null($usuario) && $usuario->getId() > 0) : ?>
                            <!--a href="#" class="pull-left" style="margin-right: 5px;">
                                <img src="<?php //echo $app->getBaseUrl() . "/usuarios/42x42/" . $usuario->getFoto(); ?>" class="img-circle" />
                            </a-->
                            <i class="fa fa-user-circle-o fa-3x pull-left"></i>
                            <a href="<?php echo $urlAlterar; ?>" style="font-weight: bold;"><?php echo $usuario->getNome(); ?></a> <br />
                            <small class="hidden-xs">
                                <a href="<?php echo $urlAlterar; ?>">Minha conta</a> |
                                <a href="<?php echo $urlLogoff; ?>">Sair</a>
                            </small>
                        <?php else : ?>
                            <i class="fa fa-user-circle-o fa-3x pull-left"></i>
                            Olá! Faça <a href="<?php echo $urlLogin; ?>">seu login</a> <br />
                            ou <a href="<?php echo $urlCadastro; ?>">cadastre-se</a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6" style="margin-bottom: 5px;">
                        <div id="carrinho">
                            <div class="icone">
                                <span class="quantidade badge">0</span>
                                <a href="<?php echo $urlCarrinho; ?>">
                                    <i class="fa fa-shopping-cart fa-3x"></i>
                                </a>
                            </div>
                            <a href="<?php echo $urlCarrinho; ?>" style="font-size: 70%;">Carrinho</a><br />
                            <a href="<?php echo $urlCarrinho; ?>" style="font-size: 130%; font-weight: bold;">
                                <small>R$</small> <span class="total">0,00</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <form method="GET" action="<?php echo $app->getBaseUrl() . "/site/" . $loja->getSlug() . "/busca" ?>" class="form-horizontal">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="p" placeholder="O que você procura?" value="<?php echo $palavraChave; ?>" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php require "menu-principal.php"; ?>
            </div>
        </div>
    </div>
</header>
<div class="content">