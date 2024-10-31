<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var bool $usaFoto
 * @var UsuarioInfo $usuario
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render($app->getBaseUrl() . "/%s/clientes") ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <?php if ($usaFoto) : ?>
                                <img src="<?php echo $usuario->getThumbnailUrl(100, 100); ?>" style="width: 100px; height: 100px;" class="img-circle" />
                            <?php else : ?>
                                <i class="fa fa-5x fa-user-circle"></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <h2><?php echo $usuario->getNome(); ?></h2>
                            <div>
                                <?php foreach ($usuario->listarGrupo() as $grupo) : ?>
                                    <span class="badge"><?php echo $grupo->getNome(); ?></span>
                                <?php endforeach; ?>
                                <span class="<?php echo $usuario->getSituacaoClasse(); ?>">
                                    <?php echo $usuario->getSituacaoStr(); ?>
                                </span>
                            </div>
                            <div>
                                <a href="mailto:<?php echo $usuario->getEmail(); ?>"><i class="fa fa-envelope"></i> <?php echo $usuario->getEmail(); ?></a>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <dl class="dl-horizontal">
                        <?php if (!isNullOrEmpty($usuario->getUltimoLogin())) : ?>
                            <dt>Último Login:</dt>
                            <dd><?php echo $usuario->getUltimoLoginStr(); ?></dd>
                        <?php endif; ?>
                        <dt>Nome:</dt>
                        <dd><?php echo $usuario->getNome(); ?></dd>
                        <?php if (!isNullOrEmpty($usuario->getCpfCnpj())) : ?>
                            <dt>CPF/CNPJ:</dt>
                            <dd><?php echo $usuario->getCpfCnpjStr(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($usuario->getTelefone())) : ?>
                            <dt>Telefone:</dt>
                            <dd><?php echo $usuario->getTelefoneStr(); ?></dd>
                        <?php endif; ?>
                        <dt>Data de Inclusão:</dt>
                        <dd><?php echo $usuario->getDataInclusaoStr(); ?></dd>
                        <dt>Última alteração:</dt>
                        <dd><?php echo $usuario->getUltimaAlteracaoStr(); ?></dd>
                    </dl>

                </div>
            </div>
            <?php if (count($usuario->listarEndereco()) > 0) : ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr>
                                <th><i class="fa fa-map-marker"></i> Endereço</th>
                                <th class="text-right"><i class="fa fa-cog"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($usuario->listarEndereco() as $endereco) : ?>
                                <?php $urlExcluir = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/excluir-endereco/" . $endereco->getId(); ?>
                                <tr>
                                    <td><?php echo $endereco->getEnderecoCompleto(); ?></td>
                                    <td class="text-right"><a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-remove"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (count($usuario->listarPreferencia()) > 0) : ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr>
                                <th>Chave</th>
                                <th>Valor</th>
                                <th class="text-right"><i class="fa fa-cog"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($usuario->listarPreferencia() as $preferencia) : ?>
                                <?php $urlExcluir = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/excluir-preferencia/" . $preferencia->getChave(); ?>
                                <tr>
                                    <td><?php echo $preferencia->getChave(); ?></td>
                                    <td><?php echo $preferencia->getValor(); ?></td>
                                    <td class="text-right"><a class="confirm" href="<?php echo $urlExcluir; ?>"><i class="fa fa-remove"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/trocar-senha"; ?>"><i class="fa fa-fw fa-lock"></i> Trocar senha</a><br />
            <a href="<?php echo $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/alterar"; ?>"><i class="fa fa-fw fa-pencil"></i> Alterar</a><br />
            <!--a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/auth/usuario/excluir/<?php echo $usuario->getId(); ?>"><i class="fa fa-fw fa-trash"></i> Excluir</a-->
        </div>
    </div>
</div>
