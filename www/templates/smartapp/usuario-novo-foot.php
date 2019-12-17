<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var string $urlVoltar
 */

$urlVoltar = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
        </div><!--col-md-6-->
    </div><!--row-->
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="<?php echo $urlVoltar; ?>" class="btn btn-lg btn-default">
                <i class="fa fa-chevron-left"></i> Continuar Comprando
            </a>
            <button type="submit" class="btn btn-lg btn-primary">
                Finalizar Pedido <i class="fa fa-chevron-right"></i>
            </button>
        </div>
    </div>
</form>