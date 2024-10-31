<?php

namespace Emagine\Loja;

use Emagine\Login\Model\GrupoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var LojaInfo $loja
 * @var string $erro
 */
$urlBase = $app->getBaseUrl() . "/" . $loja->getSlug();
$urlNovo = $app->getBaseUrl() . "/" . $loja->getSlug() . "/endereco/novo";
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $urlBase; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-user-circle"></i> Minha Conta</li>
        <li class="active"><i class="fa fa-map-marker"></i> Endereços</li>
    </ol>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?php echo $urlBase . "/alterar-meus-dados"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-user-circle"></i> Alterar Dados
                </a>
                <a href="<?php echo $urlBase . "/enderecos"; ?>" class="list-group-item active">
                    <i class="fa fa-fw fa-map-marker"></i> Endereços
                </a>
                <a href="<?php echo $urlBase . "/pedidos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-shopping-cart"></i> Pedidos feitos
                </a>
                <a href="<?php echo $urlBase . "/lista-de-desejos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-heart"></i> Lista de Desejos
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <?php if (isset($erro)) : ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fa fa-warning"></i> <?php echo $erro; ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <?php if (count($usuario->listarEndereco()) > 0) : ?>
                <?php foreach ($usuario->listarEndereco() as $endereco) : ?>
                    <?php $urlRemover = $urlBase . "/endereco/excluir/" . $endereco->getId(); ?>
                    <div class="col-md-4">
                        <div id="<?php echo "endereco-" . $endereco->getId(); ?>" class="panel panel-default panel-endereco">
                            <div class="panel-body">
                                <div class="pull-right">
                                    <a class="confirm" href="<?php echo $urlRemover; ?>"><i class="fa fa-2x fa-remove"></i></a>
                                </div>
                                <h4><?php echo $endereco->getLogradouro(); ?></h4>
                                <p>
                                    <?php echo $endereco->getEnderecoCompleto(); ?>
                                </p>
                            </div>
                            <div class="panel-footer text-right">
                                <?php startLinkEndereco($endereco, "#", "endereco-mudar btn btn-sm btn-primary"); ?>
                                <i class="fa fa-map-marker"></i> Mudar endereço
                                <?php endLinkEndereco(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-warning"></i> Nenhum endereço informado.
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <a href="<?php echo $urlNovo; ?>" class="btn btn-lg btn-primary">
                    <i class="fa fa-plus"></i> Novo Endereço
                </a>
            </div>
        </div>
    </div>
</div>
