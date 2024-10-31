<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Banner\Model\BannerPecaInfo;

/**
 * @var EmagineApp $app
 * @var BannerInfo $banner
 * @var BannerPecaInfo[] $pecas
 */
?>
<?php if (isset($pecas) && count($pecas) > 0) : ?>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php for ($i = 0; $i < count($pecas); $i++) : ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" <?php echo ($i == 0) ? "class=\"active\"" : ""; ?>></li>
        <?php endfor; ?>
    </ol>
    <div class="carousel-inner">
        <?php $i = 0; ?>
        <?php foreach ($pecas as $peca) : ?>
            <div class="<?php echo ($i == 0) ? "item active" : "item"; ?>">
                <a href="#">
                    <img src="<?php echo $peca->getImagemUrl(); ?>" alt="<?php echo $peca->getNome(); ?>"
                         style="<?php echo sprintf("width: %spx; height: %spx; margin: 0 auto;",
                             $banner->getLargura(), $banner->getAltura()); ?>" />
                </a>
            </div>
            <?php $i++; ?>
        <?php endforeach; ?>
    </div>
    <!--a class="left carousel-control" href="#myCarousel" data-slide="prev">
        <span class="fa fa-chevron-left"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
        <span class="fa fa-chevron-right"></span>
        <span class="sr-only">Next</span>
    </a-->
</div>
<?php endif; ?>