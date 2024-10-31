<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:29
 * Tablename: pais
 */ ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-reorder"></i> Países</h3>
	</div>
	<div class="panel-body">
		<form method="post" action="<?php echo $app->getBaseUrl(); ?>/localidade/pais" class="form-horizontal form-ajax">
			<input type="hidden" name="id_pais" value="<?php echo $pais->getId(); ?>" />
			<div class="form-group text-right">
				<label class="col-md-3 control-label">Nome:</label>
				<div class="col-md-9">
					<input type="text" name="nome" class="form-control" value="<?php echo $pais->getNome(); ?>" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="#localidade/pais/<?php echo $pais->getId(); ?>" class="btn btn-lg btn-default"><i class="fa fa-ban"></i> Cancelar</a>
					<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
				</div>
			</div>
		</form>
	</div>
</div>
