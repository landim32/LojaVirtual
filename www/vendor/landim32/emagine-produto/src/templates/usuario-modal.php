<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 */
$regraUsuario = new UsuarioBLL();
$usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);
?>
<div class="modal fade" id="usuarioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?php echo $app->getBaseUrl() . "/loja/usuario/adicionar"; ?>">
                <input type="hidden" name="id_loja" value="<?php echo $loja->getId(); ?>" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="fa fa-user-circle"></i> Adicionar Usuário
                    </h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group">
                        <label for="id_usuario" class="control-label col-md-3">Usuário:</label>
                        <div class="col-md-9">
                            <select id="id_usuario" name="id_usuario" class="form-control">
                                <option value="">--selecione o usuário--</option>
                                <?php foreach ($usuarios as $usuario) : ?>
                                    <option value="<?php echo $usuario->getId(); ?>"><?php echo $usuario->getNome(); ?></option>
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
                        <i class="fa fa-user-plus"></i> Adicionar Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>