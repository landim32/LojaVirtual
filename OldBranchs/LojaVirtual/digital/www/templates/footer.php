<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;

/**
 * @var EmagineApp $app
 */

?>
</div><!-- content -->
<?php echo $app->renderJavascript(); ?>
<div class="container" style="margin-bottom: 30px">
    <div class="row endereco-atual">
        <div class="col-md-6 col-md-offset-3 text-center">
            <i class="fa fa-map-marker"></i>
            <span class="completo"></span>
            <a href="<?php echo $app->getBaseUrl() . "/endereco/seleciona"; ?>"><small>(mudar)</small></a>
        </div>
    </div>
</div>
</body>
</html>
