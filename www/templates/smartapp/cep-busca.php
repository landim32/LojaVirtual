<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo[] $lojas
 */
$urlBuscaPorCep = $app->getBaseUrl() . "/busca-por-cep";
$urlBuscaPorEndereco = $app->getBaseUrl() . "/busca-por-endereco";
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="text-center">
                <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" alt="<?php echo APP_NAME; ?>" class="img-responsive" style="max-height: 120px; margin: 5px auto;" />
            </div>
            <h1 class="text-center" style="margin-bottom: 1px">
                Faça sua compras de
            </h1>
            <h3 class="text-center" style="margin-top: 1px">
                supermercado no conforto da sua casa.
            </h3>
            <form method="POST" class="form-horizontal" action="<?php echo $urlBuscaPorCep; ?>">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="form-group form-group-lg">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="cep" placeholder="Digite seu CEP" maxlength="8" />
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </div>
                        <a href="<?php echo $urlBuscaPorEndereco; ?>">Não sabe o CEP? Digite seu endereço.</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>