<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;
/**
 * @var EmagineApp $app
 * @var bool $usaFoto
 * @var bool $eAdmin
 * @var UsuarioInfo $usuarioLogado
 * @var UsuarioInfo[] $usuarioLista
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
                    <?php if ($usuarioLogado->temPermissao(UsuarioInfo::INCLUIR_USUARIO)) : ?>
                        <div class="row">
                            <div class="col-md-9">
                                <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-user-circle"></i> Usuários</h3>
                            </div>
                            <div class="col-md-3 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/usuario/novo"; ?>" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Novo usuário
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-user-circle"></i> Usuários</h3>
                    <?php endif; ?>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Email</a></th>
                            <th><a href="#">Telefone</a></th>
                            <th><a href="#">Situação</a></th>
                            <?php if ($eAdmin == true) : ?>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuarioLista as $usuario) : ?>
                            <?php
                            $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil";
                            $urlExcluir = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/excluir";
                            $podeExcluir = ($eAdmin == true && $usuarioLogado->getId() != $usuario->getId());
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo $url; ?>">
                                        <?php if ($usaFoto) : ?>
                                        <img src="<?php echo $usuario->getThumbnailUrl(22, 22); ?>" class="img-circle" style="width: 22px; height: 22px;" alt="<?php echo $usuario->getNome(); ?>" />
                                        <?php endif; ?>
                                        <?php echo $usuario->getNome(); ?>
                                    </a>
                                </td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getEmail(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $usuario->getTelefoneStr(); ?></a></td>
                                <td>
                                    <a href="<?php echo $url; ?>" class="<?php echo $usuario->getSituacaoClasse(); ?>">
                                        <?php echo $usuario->getSituacaoStr(); ?>
                                    </a>
                                </td>
                                <?php if ($eAdmin == true) : ?>
                                <td class="text-right">
                                    <?php if ($podeExcluir == true) : ?>
                                    <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                    <?php else : ?>
                                        <i class="fa fa-remove"></i>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>