<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:27
 * Tablename: bairro
 */ ?>
<div class="row">
	<div class="col-md-9">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-2 text-center">
						<i class="fa fa-user-circle" style="font-size: 80px;"></i>
					</div>
					<div class="col-md-10">
				<h2><?php echo $bairro->getNome(); ?></h2>
					</div>
				</div>
				<hr />
				<dl class="dl-horizontal">
					<dt>Cidade:</dt>
					<dd><?php echo $bairro->getCidade()->getNome(); ?></dd>
					<dt>Nome:</dt>
					<dd><?php echo $bairro->getNome(); ?></dd>
					<dt>Valor de Frete:</dt>
					<dd><?php echo $bairro->getValorFrete(); ?></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="col-md-3" style="padding-top: 40px;">
		<a href="#localidade/bairro-edit/<?php echo $bairro->getId(); ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
		<a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/localidade/bairro/excluir/<?php echo $bairro->getId(); ?>"><i class="fa fa-trash"></i> Excluir</a>
	</div>
</div>
