<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var bool $usaFoto
 * @var UsuarioInfo[] $clientes
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
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-user-circle"></i> Clientes</h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive datatable">
                        <thead>
                        <tr>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">CPF/CNPJ</a></th>
                            <th><a href="#">Email</a></th>
                            <th><a href="#">Telefone</a></th>
                            <th><a href="#">Situação</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($clientes) > 0) : ?>
                        <?php foreach ($clientes as $usuario) : ?>
                            <?php $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil"; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $url; ?>">
                                        <?php if ($usaFoto) : ?>
                                            <img src="<?php echo $usuario->getThumbnailUrl(22, 22); ?>" class="img-circle" style="width: 22px; height: 22px;" alt="<?php echo $usuario->getNome(); ?>" />
                                        <?php endif; ?>
                                        <?php echo $usuario->getNome(); ?>
                                    </a>
                                </td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getCpfCnpjStr(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getEmail(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getTelefoneStr(); ?></a></td>
                                <td>
                                    <a href="<?php echo $url; ?>" class="<?php echo $usuario->getSituacaoClasse(); ?>">
                                        <?php echo $usuario->getSituacaoStr(); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7"><i class="fa fa-warning"></i> Nenhum cliente cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>