<?php
use Emagine\Endereco\Model\BairroInfo;
/**
 * @var BairroInfo[] $bairros
 */ ?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-9">
				<h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-reorder"></i> Bairros</h3>
			</div>
			<div class="col-md-3 text-right">
				<a href="#localidade/bairro-add" class="btn btn-primary"><i class="fa fa-plus"></i> Novo Bairro</a>
			</div>
		</div>
		<hr />
		<table class="table table-striped table-hover table-responsive">
			<thead>
				<tr>
                    <th><a href="#">Bairro</a></th>
					<th><a href="#">Cidade</a></th>
                    <th><a href="#">Uf</a></th>
                    <th><a href="#">País</a></th>
					<th class="text-right"><a href="#">Valor de Frete</a></th>
					<th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
				</tr>
			</thead>
			<tbody>
            <?php if (count($bairros) > 0) : ?>
				<?php foreach ($bairros as $bairro) : ?>
                    <?php $urlBairro = $app->getBaseUrl() . "/painel-controle#localidade/bairro/" . $bairro->getId(); ?>
                    <?php $cidade = $bairro->getCidade(); ?>
                    <?php $uf = $cidade->getEstado(); ?>
				<tr>
                    <td><a href="<?php echo $urlBairro; ?>"><?php echo $bairro->getNome(); ?></a></td>
                    <td><a href="<?php echo $urlBairro; ?>"><?php echo $cidade->getNome(); ?></a></td>
                    <td><a href="<?php echo $urlBairro; ?>"><?php echo $cidade->getUf(); ?></a></td>
                    <td><a href="<?php echo $urlBairro; ?>"><?php echo $uf->getPais()->getNome(); ?></a></td>
					<td class="text-right"><a href="<?php echo $urlBairro; ?>"><?php echo $bairro->getValorFreteStr(); ?></a></td>
					<td class="text-right"><a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/localidade/bairro/excluir/<?php echo $bairro->getId(); ?>"><i class="fa fa-remove"></i></a></td>
				</tr>
				<?php endforeach; ?>
            <?php else : ?>
            <tr>
                <td colspan="5"><i class="fa fa-warning"></i> Nenhum bairro cadastrado.</td>
            </tr>
            <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
