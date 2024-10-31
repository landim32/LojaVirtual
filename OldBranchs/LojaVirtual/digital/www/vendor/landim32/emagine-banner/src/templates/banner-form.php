<?php
namespace Emagine\Banner;

use Emagine\Banner\BLL\BannerBLL;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Controls\LojaPerfil;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var BannerInfo $banner
 * @var UsuarioInfo $usuario
 * @var LojaInfo $lojas
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
 */

$regraBanner = new BannerBLL();
$urlVoltar = $app->getBaseUrl() . "/banner/listar";
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<?php echo $usuarioPerfil->render(); ?>
			<?php echo $app->getMenu("lateral"); ?>
		</div><!--col-md-3-->
		<div class="col-md-6">
			<?php if (!isNullOrEmpty($erro)) : ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-warning"></i> <?php echo $erro; ?>
				</div>
			<?php endif; ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-reorder"></i> Banners</h3>
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo $app->getBaseUrl() . "/banner"; ?>" class="form-horizontal">
                        <?php if ($banner->getId() > 0) : ?>
                            <input type="hidden" name="id_banner" value="<?php echo $banner->getId(); ?>" />
                        <?php endif; ?>
                        <div class="form-group text-right">
                            <label class="col-md-4 control-label" for="cod_tipo">Tipo de Banner:</label>
                            <div class="col-md-8">
                                <?php echo $regraBanner->dropdownListCodTipo("cod_tipo", "form-control", $banner->getCodTipo()); ?>
                            </div>
                        </div>
						<div class="form-group text-right">
							<label class="col-md-4 control-label" for="nome">Nome:</label>
							<div class="col-md-8">
								<input type="text" id="nome" name="nome" class="form-control" value="<?php echo $banner->getNome(); ?>" />
							</div>
						</div>
						<div class="form-group text-right">
							<label class="col-md-4 control-label" for="largura">Largura:</label>
							<div class="col-md-8">
								<input type="number" id="largura" name="largura" class="form-control" value="<?php echo $banner->getLargura(); ?>" />
							</div>
						</div>
						<div class="form-group text-right">
							<label class="col-md-4 control-label" for="altura">Altura:</label>
							<div class="col-md-8">
								<input type="number" id="altura" name="altura" class="form-control" value="<?php echo $banner->getAltura(); ?>" />
							</div>
						</div>
                        <div class="form-group text-right">
                            <label class="col-md-4 control-label" for="quantidade_loja">Qtde por Loja:</label>
                            <div class="col-md-8">
                                <input type="number" id="quantidade_loja" name="quantidade_loja" class="form-control" value="<?php echo $banner->getQuantidadeLoja(); ?>" />
                            </div>
                        </div>
						<div class="form-group">
							<div class="col-md-12 text-right">
							    <a href="<?php echo $urlVoltar; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
								<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!--col-md-6-->
	</div><!--row-->
</div><!--container-->
