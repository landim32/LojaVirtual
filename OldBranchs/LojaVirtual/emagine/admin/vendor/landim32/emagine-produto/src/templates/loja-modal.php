<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var LojaInfo[] $lojasCopia
 */
?>
<div class="modal fade" id="lojaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?php echo $app->getBaseUrl() . "/loja/copiar"; ?>">
                <input type="hidden" name="id_origem" value="<?php echo $loja->getId(); ?>" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="fa fa-shopping-cart"></i> Copiar Produtos para Loja
                    </h4>
                </div>
                <div class="modal-body form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-3" for="id_destino">Loja:</label>
                        <div class="col-md-9">
                            <select id="id_destino" name="id_destino" class="form-control">
                                <option value="">--selecione a loja--</option>
                                <?php foreach ($lojasCopia as $lojaCopia) : ?>
                                    <option value="<?php echo $lojaCopia->getId(); ?>"><?php echo $lojaCopia->getNome(); ?></option>
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
                        <i class="fa fa-copy"></i> Copiar Produtos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>