<?php
namespace Emagine\Banner;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Banner\Model\BannerPecaInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $lojas
 * @var UsuarioInfo $usuario
 * @var BannerInfo $banner
 * @var BannerPecaInfo $peca
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $sucesso
 * @var string $erro
 */

$urlAlterar = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/" . $peca->getId() . "/alterar";
$urlExcluir = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/" . $peca->getId() . "/excluir";
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
			<?php echo $app->getMenu("lateral"); ?>
		</div><!--col-md-3-->
		<div class="col-md-9">
			<div class="row">
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-body">
                            <img src="<?php echo $peca->getImagemUrl($banner->getLargura(), $banner->getAltura()); ?>"
                                 class="img-responsive"
                                 alt="<?php echo $peca->getNome(); ?>"
                            />
							<!--div class="row">
								<div class="col-md-2 text-center">
									<i class="fa fa-5x fa-user-circle"></i>
								</div>
								<div class="col-md-10">
									<h2><?php echo $peca->getNome(); ?></h2>
								</div>
							</div-->
							<hr />
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
							
							<dl class="dl-horizontal">
                                <dt>Nome:</dt>
                                <dd><?php echo $peca->getNome(); ?></dd>
								<dt>Banner:</dt>
								<dd><?php
                                    echo $banner->getNome() . " (" . $banner->getLargura() . "x" . $banner->getAltura() . ")";
                                    ?></dd>
								<dt>Data de Inclusão:</dt>
								<dd><?php echo $peca->getDataInclusaoStr(); ?></dd>
								<dt>Última Alteração:</dt>
								<dd><?php echo $peca->getUltimaAlteracaoStr(); ?></dd>
                                <?php if ($peca->getIdLoja() > 0) : ?>
                                <dt>Loja:</dt>
                                <dd><?php echo $peca->getLoja()->getNome(); ?></dd>
                                <?php endif; ?>
                                <?php if ($peca->getCodDestino() == BannerPecaInfo::DESTINO_LOJA && $peca->getIdLojaDestino() > 0) : ?>
                                    <dt>Loja Destino:</dt>
                                    <dd>
                                        <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                            <?php echo $peca->getLojaDestino()->getNome(); ?>
                                        </a>
                                    </dd>
                                <?php elseif ($peca->getCodDestino() == BannerPecaInfo::DESTINO_PRODUTO && $peca->getIdProduto() > 0) : ?>
                                    <dt>Produto:</dt>
                                    <dd>
                                        <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                            <?php echo $peca->getProdutoNome(); ?>
                                        </a>
                                    </dd>
                                <?php elseif ($peca->getCodDestino() == BannerPecaInfo::DESTINO_URL && !isNullOrEmpty($peca->getUrl())) : ?>
                                    <dt>Url:</dt>
                                    <dd>
                                        <a href="<?php echo $peca->getUrlDestino(); ?>" target="_blank">
                                            <?php echo $peca->getUrl(); ?>
                                        </a>
                                    </dd>
                                <?php endif; ?>
							</dl>
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/banner/" . $banner->getSlug(); ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                            </div>
						</div>
					</div>
				</div>
				<div class="col-md-3" style="padding-top: 40px;">
                    <?php if ($usuario->temPermissao(BannerPecaInfo::GERENCIAR_PECA)) : ?>
					<a href="<?php echo $urlAlterar; ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
					<a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-trash"></i> Excluir</a>
                    <?php endif; ?>
				</div>
			</div>
		</div><!--col-md-9-->
	</div><!--row-->
</div><!--container-->
