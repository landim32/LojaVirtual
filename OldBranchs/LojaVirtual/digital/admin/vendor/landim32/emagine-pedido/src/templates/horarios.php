<?php
namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pedido\Model\PedidoHorarioInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var PedidoHorarioInfo[] $horarios
 * @var string $sucesso
 * @var string $erro
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
                    <h3 style="margin-top: 5px; margin-bottom: 5px;">
                        <i class="fa fa-clock-o"></i> Horários de Entrega
                    </h3>
					<hr />
                    <?php if (!isNullOrEmpty($sucesso)) : ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-question-circle"></i> <?php echo $sucesso; ?>
                        </div>
                    <?php endif; ?>
					<?php if (!isNullOrEmpty($erro)) : ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<i class="fa fa-warning"></i> <?php echo $erro; ?>
						</div>
					<?php endif; ?>
					
					<table class="table table-striped table-hover table-responsive">
						<thead>
							<tr>
								<th><a href="#">Horário de Entrega</a></th>
								<th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
							</tr>
						</thead>
						<tbody>
							<?php if (count($horarios) > 0) : ?>
								<?php foreach ($horarios as $horario) : ?>
									<?php
                                    //$url = $app->getBaseUrl() . "/pedido-horario/" . $horario->getId();
                                    $urlExcluir = $app->getBaseUrl() . "/" . $loja->getSlug() . "/horario/excluir/" . $horario->getId();
                                    ?>
							<tr>
								<td><?php echo $horario->getHorario(); ?></td>
								<td class="text-right">
                                    <a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-remove"></i></a>
                                </td>
							</tr>
								<?php endforeach; ?>
							<?php else : ?>
							<tr>
								<td colspan="2">
									<i class="fa fa-warning"></i> Nenhum horário de entrega cadastrado!
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div><!--col-md-6-->
        <div class="col-md-3" style="padding-top: 40px;">
            <div>
                <a href="#horarioModal" data-toggle="modal" data-target="#horarioModal">
                    <i class="fa fa-fw fa-plus"></i> Adicionar Horário
                </a><br />
            </div>
        </div>
	</div><!--row-->
</div><!--container-->
