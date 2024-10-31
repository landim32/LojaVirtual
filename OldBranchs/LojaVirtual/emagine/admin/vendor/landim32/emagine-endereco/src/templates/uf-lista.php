<?php
use Emagine\Endereco\Model\UfInfo;
/**
 * @var UfInfo[] $estados
 */
 ?>
<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-reorder"></i> Estados</h3>
                <hr />
                <table class="table table-striped table-hover table-responsive">
                    <thead>
                    <tr>
                        <th><a href="#">Uf</a></th>
                        <th><a href="#">País</a></th>
                        <th><a href="#">Nome</a></th>
                        <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($estados) > 0) : ?>
                        <?php foreach ($estados as $uf) : ?>
                            <?php $urlUf = $app->getBaseUrl() . "/painel-controle#localidade/uf/" . $uf->getUf(); ?>
                        <tr>
                            <td><a href="<?php echo $urlUf; ?>"><?php echo $uf->getUf(); ?></a></td>
                            <td><a href="<?php echo $urlUf; ?>"><?php echo $uf->getPais()->getNome(); ?></a></td>
                            <td><a href="<?php echo $urlUf; ?>"><?php echo $uf->getNome(); ?></a></td>
                            <td class="text-right"><a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/localidade/uf/excluir/<?php echo $uf->getUf(); ?>"><i class="fa fa-remove"></i></a></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4">
                                <i class="fa fa-warning"></i> Nenhum estado cadastrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="padding-top: 40px;">
        <a href="#localidade/uf-add"><i class="fa fa-plus"></i> Novo Estado</a>
    </div>
</div>