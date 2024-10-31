<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:29
 * Tablename: pais
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
				<h2><?php echo $pais->getNome(); ?></h2>
					</div>
				</div>
				<hr />
				<dl class="dl-horizontal">
					<dt>Nome:</dt>
					<dd><?php echo $pais->getNome(); ?></dd>
				</dl>
			</div>
		</div>
	</div>
	<div class="col-md-3" style="padding-top: 40px;">
		<a href="#localidade/pais-edit/<?php echo $pais->getId(); ?>"><i class="fa fa-pencil"></i> Alterar</a><br />
		<a class="remove" href="<?php echo $app->getBaseUrl(); ?>/api/localidade/pais/excluir/<?php echo $pais->getId(); ?>"><i class="fa fa-trash"></i> Excluir</a>
	</div>
</div>
