<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Banner\Model\BannerPecaInfo;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Produto\Model\SeguimentoInfo;

/**
 * @var EmagineApp $app
 * @var BannerInfo $banner
 * @var BannerPecaInfo[] $pecas
 * @var SeguimentoInfo[] $seguimentos
 * @var EnderecoInfo $endereco
 * @var string $urlSeguimento
 * @var int $raio
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <a href="<?php echo $app->getBaseUrl(); ?>">
                <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>"
                    alt="<?php echo APP_NAME; ?>" class="img-responsive pull-left"
                    style="max-height: 120px; margin: 5px 10px 5px 0px;" />
            </a>
            <h1 style="margin-bottom: 1px">
                Faça suas compras de
            </h1>
            <h4 style="margin-top: 1px">
                supermercado no conforto da sua casa.
            </h4>
        </div>
    </div>
    <?php //var_dump($pecas); ?>
    <?php require __DIR__ . "/banner.php"; ?>
    <form id="raioForm" method="POST" class="form-horizontal margin-top-25px">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 text-center">
                <div class="form-group form-group-lg">
                    <div class="input-group input-group-lg">
                        <input type="number" class="form-control" id="raio" name="raio"
                               value="<?php echo $raio; ?>"
                               placeholder="Raio de busca (ex: 100km)" maxlength="4" />
                        <span class="input-group-addon">Km</span>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </div>
                <!--span class="text-muted">Informa o raio de busca e encontre o q procura.</span-->
            </div>
        </div>
    </form>
    <div class="row margin-top-25px">
        <div class="col-md-8 col-md-offset-2">
            <?php if (count($seguimentos) > 0) : ?>
            <div class="row">
                <?php foreach ($seguimentos as $seguimento) : ?>
                <?php
                    if (!is_null($urlSeguimento)){
                        $url = sprintf($urlSeguimento, $seguimento->getSlug());
                    }
                    else {
                        $url = $app->getBaseUrl() . "/s/" . $seguimento->getSlug();
                    }
                    ?>
                    <div class="col-md-3 text-center">
                        <a href="<?php echo $url; ?>" class="btn btn-primary btn-xl btn-circle">
                            <img src="<?php echo $seguimento->getIconeUrl(35, 35); ?>"
                                 style="width: 35px; height: 35px; margin: 5px 0px;"
                                 alt="<?php echo $seguimento->getNome(); ?>">
                        </a><br />
                        <a href="<?php echo $url; ?>"><?php echo $seguimento->getNome(); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fa fa-warning"></i> Nenhum seguimento encontrado dentro do raio de <?php echo $raio; ?>Km.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if (!is_null($endereco)) : ?>
<div class="container margin-top-30px">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <i class="fa fa-map-marker"></i>
            <span class="completo"><?php echo $endereco->getEnderecoCompleto(true, false); ?></span>
            <a href="<?php echo $app->getBaseUrl() . "/endereco/seleciona"; ?>"><small>(mudar)</small></a>
        </div>
    </div>
</div>
<?php endif; ?>