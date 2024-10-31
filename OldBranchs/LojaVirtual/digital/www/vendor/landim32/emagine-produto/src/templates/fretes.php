<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var LojaFreteInfo[] $fretes
 * @var LojaInfo $loja
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
                    <div class="row">
                        <div class="col-md-9">
                            <h3 style="margin-top: 5px; margin-bottom: 5px;">
                                <i class="fa fa-truck"></i> Valores de Frete
                            </h3>
                        </div>
                        <div class="col-md-3 text-right">
                            <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/frete/inserir"; ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Novo Frete</a>
                        </div>
                    </div>
                    <hr />
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>Uf</th>
                            <th>Cidade</th>
                            <th>Bairro</th>
                            <th>Logradouro</th>
                            <th class="text-right">Valor</th>
                            <th>Entrega</th>
                            <th class="text-right"><i class="fa fa-cog"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($fretes) > 0) : ?>
                            <?php foreach ($fretes as $frete) : ?>
                                <?php $urlFrete = $app->getBaseUrl() . "/" . $loja->getSlug() . "/frete/" . $frete->getId(); ?>
                                <?php $urlRemover = $app->getBaseUrl() . "/" . $loja->getSlug() . "/frete/excluir/" . $frete->getId(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $urlFrete; ?>">
                                            <?php echo (!isNullOrEmpty($frete->getUf())) ? $frete->getUf() : "Todos" ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $urlFrete; ?>">
                                            <?php echo (!isNullOrEmpty($frete->getCidade())) ? $frete->getCidade() : "Todas as cidades" ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $urlFrete; ?>">
                                            <?php echo (!isNullOrEmpty($frete->getBairro())) ? $frete->getBairro() : "Todos os bairros" ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo $urlFrete; ?>">
                                            <?php echo (!isNullOrEmpty($frete->getLogradouro())) ? $frete->getLogradouro() : "Todos os logradouros" ?>
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo $urlFrete; ?>">
                                            <?php echo $frete->getValorFreteStr(); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($frete->getEntrega()) : ?>
                                            <a href="<?php echo $urlFrete; ?>" class="label label-success"><i class="fa fa-truck"></i> Entrega</a>
                                        <?php else : ?>
                                            <a href="<?php echo $urlFrete; ?>" class="label label-danger"><i class="fa fa-remove"></i> Não entrega</a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <a class="confirm" href="<?php echo $urlRemover; ?>"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7">
                                    <i class="fa fa-warning"></i> Entrega em todo o Brasil de forma gratuita.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>