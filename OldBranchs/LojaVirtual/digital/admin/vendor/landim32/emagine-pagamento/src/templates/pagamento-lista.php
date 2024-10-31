<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * @var EmagineApp $app
 * @var bool $usaFoto
 * @var string $urlFormato
 * @var PagamentoInfo[] $pagamentos
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render($urlFormato); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-dollar"></i> Pagamentos</h3>
                    <hr />
                    <table class="table table-striped table-hover table-responsive datatable">
                        <thead>
                        <tr>
                            <th class="text-right"><a href="#">Id</a></th>
                            <th><a href="#">Nome</a></th>
                            <th><a href="#">Forma Pgto</a></th>
                            <th class="text-right"><a href="#">Valor</a></th>
                            <th><a href="#">Situação</a></th>
                            <th class="text-right"><i class="fa fa-cog"></i></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pagamentos as $pagamento) : ?>
                            <?php $url = $app->getBaseUrl() . "/pagamento/" . $pagamento->getId(); ?>
                            <?php $urlExcluir = $app->getBaseUrl() . "/pagamento/excluir/" . $pagamento->getId(); ?>
                            <tr>
                                <td class="text-right">
                                    <a href="<?php echo $url; ?>">#<?php echo $pagamento->getId(); ?></a>
                                </td>
                                <td><a href="<?php echo $url; ?>"><?php echo $pagamento->getUsuario()->getNome(); ?></a></td>
                                <td><a href="<?php echo $url; ?>"><?php echo $pagamento->getTipo(); ?></a></td>
                                <td class="text-right">
                                    <a href="<?php echo $url; ?>"><?php echo $pagamento->getTotalStr(); ?></a>
                                </td>
                                <td>
                                    <a href="<?php echo $url; ?>" class="<?php echo $pagamento->getSituacaoClasse(); ?>">
                                        <span class=""><?php echo $pagamento->getSituacao(); ?>
                                    </a>
                                </td>
                                <td class="text-right">
                                    <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                        <i class="fa fa-remove"></i>
                                    </a>
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