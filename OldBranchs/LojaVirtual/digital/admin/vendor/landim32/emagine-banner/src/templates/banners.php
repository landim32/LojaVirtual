<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 13/10/2018
 * Time: 18:01
 * Tablename: banner
 */

namespace Emagine\Banner;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Banner\Model\BannerInfo;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var BannerInfo[] $banners
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
 * @var bool $podeEditarBanner
 */

?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
			<?php echo $app->getMenu("lateral"); ?>
		</div><!--col-md-3-->
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-picture-o"></i> Áreas de Banners</h3>
					<hr />
					<?php if (!isNullOrEmpty($erro)) : ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<i class="fa fa-warning"></i> <?php echo $erro; ?>
						</div>
					<?php endif; ?>
					
					<table class="table table-striped table-hover table-responsive">
						<thead>
							<tr>
								<th><a href="#">Nome</a></th>
                                <?php if ($podeEditarBanner) : ?>
								<th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                                <?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php if (count($banners) > 0) : ?>
								<?php foreach ($banners as $banner) : ?>
									<?php $url = $app->getBaseUrl() . "/banner/" . $banner->getSlug(); ?>
							        <tr>
								        <td>
                                            <a href="<?php echo $url; ?>">
                                                <?php echo $banner->getNome(); ?> (<?php echo $banner->getLargura() . "x" . $banner->getAltura(); ?>)
                                                <?php if ($podeEditarBanner && $banner->getCodTipo() == BannerInfo::ADMIN) : ?>
                                                    <span class="label label-primary">Administrativo</span>
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                        <?php if ($podeEditarBanner) : ?>
                                            <?php
                                            $urlAlterar = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/alterar";
                                            $urlExcluir = $app->getBaseUrl() . "/banner/" . $banner->getSlug() . "/excluir";
                                            ?>
                                            <td class="text-right">
                                                <a href="<?php echo $urlAlterar; ?>"><i class="fa fa-pencil"></i></a>
                                                <a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-remove"></i></a>
                                            </td>
                                        <?php endif; ?>
							        </tr>
								<?php endforeach; ?>
							<?php else : ?>
							<tr>
								<td colspan="<?php echo ($podeEditarBanner) ? "2" : "1" ?>">
									<i class="fa fa-warning"></i> Nenhum banner cadastrado!
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div><!--col-md-9-->
        <div class="col-md-3" style="padding-top: 40px;">
            <?php if ($podeEditarBanner) : ?>
            <a href="<?php echo $app->getBaseUrl() . "/banner/novo"; ?>">
                <i class="fa fa-plus"></i> Novo espaço
            </a>
            <?php endif; ?>
        </div>
	</div><!--row-->
</div><!--container-->
