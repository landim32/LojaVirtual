<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var UsuarioPerfilBLL $usuarioPerfil
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
                            <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-users"></i> Grupos</h3>
                            <hr />
                            <table id="grupoTable" class="table table-striped table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th><a href="#">Nome</a></th>
                                    <th><a href="#">Permissões</a></th>
                                    <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="3">Carregando...</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3" style="padding-top: 40px;">
                    <a href="#" class="grupo-inserir"><i class="fa fa-pencil"></i> Novo Grupo</a><br />
                </div>
            </div>
        </div>
    </div>
</div>