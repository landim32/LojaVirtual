<?php

namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 * @var string[] $situacoes
 * @var bool $exibeFoto
 * @var string $erro
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
<?php //echo $app->renderModal(); ?>
<div class="container">
    <h2>Pedido #<?php echo $pedido->getId(); ?>(R$ <?php echo $pedido->getTotalStr(); ?>)</h2>
    <span class="badge"><?php echo $pedido->getEntregaStr(); ?></span>
    <span class="badge"><?php echo $pedido->getPagamentoStr(); ?></span>
    <span class="badge"><?php echo $pedido->getSituacaoStr(); ?></span>
    <hr />
    <?php if (!isNullOrEmpty($erro)) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fa fa-warning"></i> <?php echo $erro; ?>
        </div>
    <?php endif; ?>
    <?php require __DIR__ . "/pedido-dado.php"; ?>
</div>
<?php echo $app->renderJavascript(); ?>
<script type="text/javascript">
    window.print();
</script>
</body>
</html>
