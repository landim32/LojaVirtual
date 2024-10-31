<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo[] $lojas
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $callback
 * @var string $erro
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
                    <h3 style="margin-top: 5px; margin-bottom: 5px;">
                        <i class="fa fa-shopping-cart"></i> Selecione a Loja
                    </h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive datatable">
                        <thead>
                        <tr>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Endereço</a></th>
                            <th><a href="#">Situação</a></th>
                            <th class="text-right"><a href="#">Quant.</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($lojas as $loja) : ?>
                            <?php
                            //$urlLoja = sprintf($callback, $loja->getSlug());
                            $urlLoja = $app->getBaseUrl() . "/loja/" . $loja->getSlug() . "/mudar?callback=" . urlencode($callback);
                            ?>
                            <tr>
                                <td><a href="<?php echo $urlLoja; ?>"><?php echo $loja->getNome(); ?></a></td>
                                <td><a href="<?php echo $urlLoja; ?>"><?php echo $loja->getEnderecoCompleto(); ?></a></td>
                                <td>
                                    <a href="<?php echo $urlLoja; ?>" class="<?php echo $loja->getSituacaoClasse(); ?>">
                                        <?php echo $loja->getSituacaoStr(); ?>
                                    </a>
                                </td>
                                <td class="text-right">
                                    <a href="<?php echo $urlLoja; ?>"><?php echo $loja->getQuantidade(); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>