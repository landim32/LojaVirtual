<?php
namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pedido\BLL\PedidoHorarioBLL;
use Emagine\Pedido\Model\PedidoHorarioInfo;


use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoHorarioInfo $horario
 * @var string $erro
 */

$regraHorario = new PedidoHorarioBLL();
?>
<div class="modal fade" id="horarioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/horario"; ?>">
                <input type="hidden" name="id_origem" value="<?php echo $loja->getId(); ?>" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="fa fa-clock-o"></i> Horário de Entrega
                    </h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group text-right">
                        <label class="col-md-2 control-label">Entre:</label>
                        <div class="col-md-4">
                            <?php echo $regraHorario->dropdownList("inicio", "form-control", $horario->getInicio()); ?>
                        </div>
                        <label class="col-md-2 control-label" style="text-align: center">e</label>
                        <div class="col-md-4">
                            <?php echo $regraHorario->dropdownList("fim", "form-control", $horario->getFim()); ?>
                        </div>
                    </div>
                    <div class="form-group text-right">


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="fa fa-clock-o"></i> Adicionar Horário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>