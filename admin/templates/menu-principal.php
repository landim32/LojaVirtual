<?php
namespace Emagine\Erp;

use Emagine\Base\EmagineApp;

/**
 * @var EmagineApp $app
 */
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Alterar navegação</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=""><?php echo APP_NAME; ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <?php echo $app->getMenu("main"); ?>
            <?php echo $app->getMenu("right"); ?>
        </div><!--/.nav-collapse -->
    </div>
</nav>