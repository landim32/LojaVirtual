<?php
use Emagine\Endereco\Model\CidadeInfo;
/**
 * @var CidadeInfo[] $cidades
 */ ?>
<div class="panel panel-default">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-9">
				<h3 style="margin-top: 5px; margin-bottom: 5px;"><i class="fa fa-reorder"></i> Cidades</h3>
			</div>
			<div class="col-md-3 text-right">
				<a href="#localidade/cidade-add" class="btn btn-primary"><i class="fa fa-plus"></i> Nova Cidade</a>
			</div>
		</div>
		<hr />
		<table class="table table-striped table-hover table-responsive">
			<thead>
				<tr>
					<th><a href="#">Cidade</a></th>
                    <th><a href="#">Uf</a></th>
                    <th><a href="#">País</a></th>
					<th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
				</tr>
			</thead>
			<tbody>
            <?php if (count($cidades) > 0) : ?>
				<?php foreach ($cidades as $cidade) : ?>
                    <?php $urlCidade = $app->getBaseUrl() . "/painel-controle#localidade/cidade/" . $cidade->getId(); ?>
				<tr>
                    <td><a href="<?php echo $urlCidade; ?>"><?php echo $cidade->getNome(); ?></a></td>
					<td><a href="<?php echo $urlCidade; ?>"><?php echo $cidade->getUf(); ?></a></td>
                    <td><a href="<?php echo $urlCidade; ?>"><?php echo $cidade->getPaisNome(); ?></a></td>
					<td class="text-right"><a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/localidade/cidade/excluir/<?php echo $cidade->getId(); ?>"><i class="fa fa-remove"></i></a></td>
				</tr>
				<?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4"><i class="fa fa-warning"></i> Nenhuma cidade cadastrada.</td>
                </tr>
            <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>
