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
 */
$url = $app->getBaseUrl() . "/" . $loja->getSlug();
$urlNovo = $app->getBaseUrl() . "/" . $loja->getSlug() . "/endereco/novo";
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-user-circle"></i> Minha Conta</li>
        <li class="active"><i class="fa fa-map-marker"></i> Endereços</li>
    </ol>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?php echo $url . "/alterar-meus-dados"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-user-circle"></i> Alterar Dados
                </a>
                <a href="<?php echo $url . "/enderecos"; ?>" class="list-group-item active">
                    <i class="fa fa-fw fa-map-marker"></i> Endereços
                </a>
                <a href="<?php echo $url . "/pedidos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-shopping-cart"></i> Pedidos feitos
                </a>
                <a href="<?php echo $url . "/lista-de-desejos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-heart"></i> Lista de Desejos
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" class="form-horizontal">
