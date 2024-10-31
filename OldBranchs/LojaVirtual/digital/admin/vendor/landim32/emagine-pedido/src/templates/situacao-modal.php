<?php
namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Pedido\BLL\PedidoHorarioBLL;
use Emagine\Pedido\Model\PedidoHorarioInfo;


use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 * @var UsuarioInfo $usuario
 * @var array<string,string> $situacoes
 * @var string $erro
 */
?>
<div class="modal fade" id="situacaoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedido/id/" . $pedido->getId(); ?>">
                <input type="hidden" name="id_pedido" value="<?php echo $pedido->getId(); ?>" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="fa fa-shopping-cart"></i> Situação do Pedido
                    </h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group text-right">
                        <label class="col-md-3 control-label" for="mensagem">Mensagem:</label>
                        <div class="col-md-9">
                            <textarea id="mensagem" name="mensagem" class="form-control"
                               placeholder="Opcional. Caso não seja preenchido será enviada uma mensagem padrão de acordo com a situação"
                                      rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <label class="col-md-3 control-label" for="cod_situacao">Situação:</label>
                        <div class="col-md-9">
                            <select id="cod_situacao" name="cod_situacao" class="form-control">
                                <?php foreach ($situacoes as $cod_situacao => $nome) : ?>
                                    <option value="<?php echo $cod_situacao?>"<?php
                                    echo ($pedido->getCodSituacao() == $cod_situacao) ? " selected='selected'" : "";
                                    ?>><?php echo $nome; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-default" data-dismiss="modal">
                        <i class="fa fa-remove"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-lg btn-primary">
                        <i class="fa fa-envelope"></i> Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>