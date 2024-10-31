<?php
namespace Emagine\Banner;

use Emagine\Banner\Model\BannerPecaInfo;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var BannerInfo $banner
 * @var BannerPecaInfo[] $pecas
 * @var LojaInfo[] $lojas
 * @var int $id_loja
 * @var UsuarioInfo $usuario
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $sucesso
 * @var string $erro
 */

$urlBannerAlterar = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/alterar";
$urlBannerExcluir = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/excluir";
$urlNovaPeca = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/nova?banner=" . $banner->getId();
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php echo $usuarioPerfil->render(); ?>
            <?php if (count($lojas) > 0) : ?>
            <div class="list-group">
                <a href="<?php echo $app->getBaseUrl() . "/banner/" . $banner->getSlug(); ?>" class="<?php
                echo (!($id_loja > 0)) ? "list-group-item active" : "list-group-item";
                ?>">
                    <i class="fa fa-fw fa-shopping-cart"></i> Todas as lojas
                </a>
                <?php foreach ($lojas as $loja) : ?>
                    <?php $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "?loja=" . $loja->getId(); ?>
                    <a href="<?php echo $url; ?>" class="<?php
                    echo ($id_loja == $loja->getId()) ? "list-group-item active" : "list-group-item";
                    ?>"">
                        <i class="fa fa-fw fa-shopping-cart"></i> <?php echo $loja->getNome(); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
			<?php echo $app->getMenu("lateral"); ?>
		</div><!--col-md-3-->
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-9">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <i class="fa fa-5x fa-picture-o"></i>
                        </div>
                        <div class="col-md-10">
                            <h2 style="margin: 5px 0">
                                <?php
                                echo $banner->getNome() . " (" . $banner->getLargura() .
                                    "x" . $banner->getAltura() . ")";
                                ?>
                            </h2>
                            <span>Banners vísiveis por Loja:</span>
                            <span class="badge"><?php echo $banner->getQuantidadeLoja(); ?></span>
                        </div>
                    </div>
                    <?php if (!isNullOrEmpty($sucesso)) : ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-question-circle"></i> <?php echo $sucesso; ?>
                        </div>
                    <?php elseif (!isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <?php if (count($pecas) > 0) : ?>
                            <?php $quantidadeAtual = 0; ?>
                            <?php foreach ($pecas as $peca) : ?>
                                <?php
                                $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/" . $peca->getId();
                                if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
                                    $arquivado = false;
                                }
                                else {
                                    $arquivado = $quantidadeAtual >= $banner->getQuantidadeLoja();
                                }
                                ?>
                                <div class="<?php echo $arquivado ? "panel panel-default text-danger" : "panel panel-default"; ?>">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <a href="<?php echo $url; ?>" class="thumbnail">
                                                    <img src="<?php echo $peca->getImagemUrl($banner->getLargura(), $banner->getAltura()); ?>"
                                                         class="img-responsive"
                                                         alt="<?php echo $peca->getNome(); ?>"
                                                    />
                                                </a>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="pull-right" style="font-size: 20px; margin-left: 5px">
                                                    <a href="<?php echo $url . "/mover-abaixo"; ?>" class="<?php echo $arquivado ? "text-danger" : ""; ?>"><i class="fa fa-arrow-circle-up"></i></a><br />
                                                    <a href="<?php echo $url . "/mover-acima"; ?>" class="<?php echo $arquivado ? "text-danger" : ""; ?>"><i class="fa fa-arrow-circle-down"></i></a><br />
                                                    <a href="<?php echo $url . "/excluir"; ?>" class="<?php echo $arquivado ? "confirm text-danger" : "confirm"; ?>"><i class="fa fa-remove"></i></a><br />
                                                </div>
                                                <?php if ($arquivado) : ?>
                                                    <div class="pull-right">
                                                        <i class="fa fa-download"></i> Arquivado<br />
                                                    </div>
                                                <?php endif; ?>
                                                <h4 style="margin-top: 0px">
                                                    <a href="<?php echo $url; ?>" class="<?php echo $arquivado ? "text-danger" : ""; ?>">
                                                        <strong><?php echo $peca->getNome(); ?></strong>
                                                    </a>
                                                </h4>
                                                <p style="font-size: 80%">
                                                    <?php if ($peca->getIdLoja() > 0) : ?>
                                                        <span>Loja:</span>
                                                        <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                                            <?php echo $peca->getLoja()->getNome(); ?>
                                                        </a><br />
                                                    <?php endif; ?>
                                                    <?php if ($peca->getCodDestino() == BannerPecaInfo::DESTINO_LOJA && $peca->getIdLojaDestino() > 0) : ?>
                                                    <span>Loja Destino:</span>
                                                    <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                                        <?php echo $peca->getLojaDestino()->getNome(); ?>
                                                    </a><br />
                                                    <?php elseif ($peca->getCodDestino() == BannerPecaInfo::DESTINO_PRODUTO && $peca->getIdProduto() > 0) : ?>
                                                    <span>Produto:</span>
                                                    <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                                        <?php echo $peca->getProdutoNome(); ?>
                                                    </a><br />
                                                    <?php elseif ($peca->getCodDestino() == BannerPecaInfo::DESTINO_URL && !isNullOrEmpty($peca->getUrl())) : ?>
                                                    <span>Url:</span>
                                                    <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                                        <?php echo $peca->getUrl(); ?>
                                                    </a><br />
                                                    <?php endif; ?>
                                                    Ordem: <?php echo $peca->getOrdem(); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $quantidadeAtual++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> Nenhum banner cadastrado!
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="<?php echo $app->getBaseUrl() . "/banner/listar"; ?>" class="btn btn-lg btn-default">
                                <i class="fa fa-chevron-left"></i> Voltar
                            </a>
                        </div>
                    </div>
				</div>
				<div class="col-md-3" style="padding-top: 40px;">
                    <?php if ($usuario->temPermissao(BannerPecaInfo::GERENCIAR_PECA)) : ?>
                    <a href="<?php echo $urlNovaPeca; ?>"><i class="fa fa-plus"></i> Nova Peça</a>
                    <br /><br />
                    <?php endif; ?>
                    <?php if ($usuario->temPermissao(BannerInfo::GERENCIAR_BANNER)) : ?>
					    <a href="<?php echo $urlBannerAlterar; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
					    <a class="confirm" href="<?php echo $urlBannerExcluir; ?>"><i class="fa fa-trash"></i> Excluir</a>
                        <br /><br />
                    <?php endif; ?>
                    <a href="<?php echo $app->getBaseUrl() . "/banner/listar"; ?>">
                        <i class="fa fa-chevron-left"></i> Voltar
                    </a>
				</div>
			</div>
		</div><!--col-md-9-->
	</div><!--row-->
</div><!--container-->
