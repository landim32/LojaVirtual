<?php
namespace Emagine\Loja;

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
    <title><?php echo APP_NAME; ?></title>
    <?php echo $app->renderCss(); ?>
</head>
<body>