<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var LojaFreteInfo $frete
 */
?>

<div class="form-group form-group-lg">
    <label class="col-md-3 control-label" for="valor_frete">Valor:</label>
    <div class="col-md-4">
        <input type="text" id="valor_frete" name="valor_frete" class="form-control money"
        value="<?php echo number_format($frete->getValorFrete(), 2, ",", ""); ?>" />
    </div>
    <div class="col-md-5">
        <label class="checkbox-inline">
            <input type="checkbox" id="entrega" name="entrega" value="1" <?php
            echo ($frete->getEntrega() == true) ? "checked='checked'" : "";
            ?> /> Realiza entrega nesse local
        </label>
    </div>
</div>
<div class="form-group">
    <div class="col-md-12 text-right">
        <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/fretes"; ?>" class="btn btn-lg btn-default">
            <i class="fa fa-chevron-left"></i> Voltar
        </a>
        <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
    </div>
</div>
</form></div></div></div></div></div>