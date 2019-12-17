<?php

namespace Emagine\Loja;

use Emagine\Login\Model\GrupoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var string $erro
 */
//$url = $app->getBaseUrl() . "/" . $loja->getSlug();
//$urlNovo = $app->getBaseUrl() . "/" . $loja->getSlug() . "/endereco/novo";
?>
<div class="container">
    <div class="text-center">
        <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" alt="<?php echo APP_NAME; ?>" class="img-responsive" style="max-height: 120px; margin: 5px auto;" />
    </div>
    <h3 class="text-center">Selecione o endereço onde deseja receber suas compras:</h3>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php foreach ($usuario->listarEndereco() as $endereco) : ?>
                        <?php $url = $app->getBaseUrl() . "/endereco/busca/" . $endereco->getId(); ?>
                        <h3>
                            <?php startLinkEndereco($endereco, $url); ?>
                            <?php echo $endereco->getLogradouro(); ?>
                            <?php endLinkEndereco(); ?>
                        </h3>
                        <p><?php echo $endereco->getEnderecoCompleto(); ?></p>
                        <hr />
                    <?php endforeach; ?>
                    <div class="text-right">
                        <a href="<?php echo $app->getBaseUrl() . "/loja/busca-por-cep"; ?>" class="btn btn-lg btn-primary">
                            <i class="fa fa-plus"></i> Adicionar endereço
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
