<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\PermissaoInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var PermissaoInfo[] $permissoes
 */

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-lock"></i> Permissões</h3>
                            <hr />
                            <table class="table table-striped table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th><a href="#">Slug</a></th>
                                    <th><a href="#">Nome</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($permissoes as $permissao) : ?>
                                    <tr>
                                        <td><?php echo $permissao->getSlug(); ?></td>
                                        <td><?php echo $permissao->getNome(); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>