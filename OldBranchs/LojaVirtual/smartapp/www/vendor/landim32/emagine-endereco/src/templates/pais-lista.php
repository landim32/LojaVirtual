<?php
use Emagine\Endereco\Model\PaisInfo;
/**
 * @var PaisInfo[] $paises
 */ ?>
<div class="row">
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-globe"></i> Países</h3>
                <hr />
                <table class="table table-striped table-hover table-responsive">
                    <thead>
                    <tr>
                        <th><a href="#">Nome</a></th>
                        <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($paises as $pais) : ?>
                        <?php $urlPais = $app->getBaseUrl() . "/painel-controle#localidade/pais/" . $pais->getId(); ?>
                        <tr>
                            <td><a href="<?php echo $urlPais; ?>"><?php echo $pais->getNome(); ?></a></td>
                            <td class="text-right"><a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/localidade/pais/excluir/<?php echo $pais->getId(); ?>"><i class="fa fa-remove"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="padding-top: 40px;">
        <a href="#localidade/pais-add"><i class="fa fa-plus"></i> Novo País</a>
    </div>
</div>
