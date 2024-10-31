<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo[] $lojas
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
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="<?php echo get_tema_path(); ?>/favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo get_tema_path(); ?>/favicon.ico">
    <title><?php echo APP_NAME; ?></title>
    <?php echo $app->renderCss(); ?>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="text-center">
                <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" alt="AJ Supermercados" class="img-responsive" style="max-height: 120px; margin: 5px auto;" />
            </div>
            <h3 class="text-center">Selecione a cidade onde deseja fazer suas compras:</h3>

            <div class="list-group">
                <?php foreach ($lojas as $loja) : ?>
                    <?php $urlLoja = $app->getBaseUrl() . "/site/" . $loja->getSlug(); ?>
                    <a href="<?php echo $urlLoja; ?>" class="list-group-item">
                        <h4 class="list-group-item-heading"><?php echo $loja->getNome(); ?></h4>
                        <p class="list-group-item-text"><?php echo $loja->getDescricao(); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php echo $app->renderJavascript(); ?>
</body>
</html>
