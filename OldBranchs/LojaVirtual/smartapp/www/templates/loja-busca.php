<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var UsuarioInfo|null $usuario
 * @var EnderecoInfo $endereco
 * @var LojaInfo[] $lojas
 */
?>
<div class="container">
    <div class="text-center">
        <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" alt="<?php echo APP_NAME; ?>" class="img-responsive" style="max-height: 120px; margin: 5px auto;" />
    </div>
    <h3 class="text-center">Selecione a loja onde deseja fazer suas compras:</h3>
    <?php require __DIR__ . "/banner.php"; ?>
    <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($lojas as $loja) : ?>
            <?php $urlLoja = $app->getBaseUrl() . "/" . $loja->getSlug(); ?>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a href="<?php echo $urlLoja; ?>" style="display: block">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="<?php echo $loja->getFotoUrl(); ?>" class="img-rounded" />
                                </div>
                                <div class="col-md-9">
                                    <h3><strong><?php echo $loja->getNome(); ?></strong></h3>
                                    <i class="fa fa-map-marker"></i> <strong><?php echo $loja->getDistanciaStr(); ?></strong><br />
                                    <?php echo $loja->getEnderecoCompleto(); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            $i++;
            if ($i % 2 == 0) {
                echo "</div><div class='row'>";
            }
            ?>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <i class="fa fa-map-marker"></i>
            <span class="completo"><?php echo $endereco->getEnderecoCompleto(true, false); ?></span>
            <a href="<?php echo $app->getBaseUrl() . "/endereco/seleciona"; ?>"><small>(mudar)</small></a>
        </div>
    </div>
</div>