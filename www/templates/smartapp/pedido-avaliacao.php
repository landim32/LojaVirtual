<?php

namespace Emagine\Loja;

use Emagine\Login\Model\GrupoInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Base\EmagineApp;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 * @var GrupoInfo[]|null $grupos
 * @var string[]|null $situacoes
 * @var string @erro
 */
$url = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="<?php echo $url; ?>"><i class="fa fa-home"></i> Home</a></li>
        <li><i class="fa fa-user-circle"></i> Minha Conta</li>
        <li class="active"><i class="fa fa-user-circle"></i> Alterar Dados</li>
    </ol>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="<?php echo $url . "/alterar-meus-dados"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-user-circle"></i> Alterar Dados
                </a>
                <a href="<?php echo $url . "/enderecos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-map-marker"></i> Endereços
                </a>
                <a href="<?php echo $url . "/pedidos"; ?>" class="list-group-item active">
                    <i class="fa fa-fw fa-shopping-cart"></i> Pedidos feitos
                </a>
                <a href="<?php echo $url . "/lista-de-desejos"; ?>" class="list-group-item">
                    <i class="fa fa-fw fa-heart"></i> Lista de Desejos
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-user-circle-o"></i> Avaliar Pedido</h3>
                </div>
                <div class="panel-body">
                    <form method="post" class="form-horizontal">
                        <input type="hidden" name="id_pedido" value="<?php echo $pedido->getId(); ?>" />
                        <?php if (isset($erro)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group form-group-lg">
                            <label class="control-label col-md-3" for="nome">Avaliação:</label>
                            <div class="col-md-9">
                                <div class="btn-group" data-toggle="buttons" id="nota">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <label class="btn">
                                        <input type="radio" name="nota" value="<?php echo $i; ?>">
                                        <i class="fa fa-star"></i> <?php echo $i; ?>
                                    </label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="control-label col-md-3" for="comentario">Comentário:</label>
                            <div class="col-md-9">
                                <textarea id="comentario" name="comentario" class="form-control"
                                   rows="3" placeholder="Preencha o comentário"
                                ><?php echo $pedido->getComentario(); ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/pedidos"; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Voltar
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-envelope"></i> Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
