<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;

/**
 * @var EmagineApp $app
 * @var EnderecoInfo $endereco
 */

?>
</div><!-- content -->
<?php echo $app->renderJavascript(); ?>
<div class="container" style="margin-bottom: 30px">
    <!--div class="row endereco-atual"-->
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <i class="fa fa-map-marker"></i>
            <span class="completo"><?php echo $endereco->getEnderecoCompleto(true, false); ?></span>
            <a href="<?php echo $app->getBaseUrl() . "/endereco/seleciona"; ?>"><small>(mudar)</small></a>
        </div>
    </div>
</div>
</body>
</html>
