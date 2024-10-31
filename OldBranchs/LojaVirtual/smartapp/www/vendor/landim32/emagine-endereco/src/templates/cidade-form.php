<?php
use Emagine\Endereco\BLL\UfBLL;
$regraUf = new UfBLL();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-reorder"></i> Cidades</h3>
	</div>
	<div class="panel-body">
		<form method="post" action="<?php echo $app->getBaseUrl(); ?>/localidade/cidade" class="form-horizontal form-ajax">
			<input type="hidden" name="id_cidade" value="<?php echo $cidade->getId(); ?>" />
			<div class="form-group text-right">
				<label class="col-md-3 control-label">Uf:</label>
				<div class="col-md-9">
                    <?php echo $regraUf->dropdownList("uf", "form-control", $cidade->getUf()); ?>
				</div>
			</div>
			<div class="form-group text-right">
				<label class="col-md-3 control-label">Nome:</label>
				<div class="col-md-9">
					<input type="text" name="nome" class="form-control" value="<?php echo $cidade->getNome(); ?>" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="#localidade/cidade/<?php echo $cidade->getId(); ?>" class="btn btn-lg btn-default"><i class="fa fa-ban"></i> Cancelar</a>
					<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
				</div>
			</div>
		</form>
	</div>
</div>
