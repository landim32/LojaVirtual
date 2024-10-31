<?php
use Emagine\Endereco\BLL\CidadeBLL;
use Emagine\Endereco\BLL\BairroBLL;
use Emagine\Endereco\Model\BairroInfo;
/**
 * @var BairroInfo $bairro
 */

$regraCidade = new CidadeBLL();
 ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-reorder"></i> Bairros</h3>
	</div>
	<div class="panel-body">
		<form method="post" action="<?php echo $app->getBaseUrl(); ?>/localidade/bairro" class="form-horizontal form-ajax">
			<input type="hidden" name="id_bairro" value="<?php echo $bairro->getId(); ?>" />
			<div class="form-group text-right">
				<label class="col-md-3 control-label">Cidade:</label>
				<div class="col-md-9">
                    <?php echo $regraCidade->dropdownList("id_cidade", "form-control", $bairro->getIdCidade()); ?>
				</div>
			</div>
			<div class="form-group text-right">
				<label class="col-md-3 control-label">Nome:</label>
				<div class="col-md-9">
					<input type="text" name="nome" class="form-control" value="<?php echo $bairro->getNome(); ?>" />
				</div>
			</div>
			<div class="form-group text-right">
				<label class="col-md-3 control-label">Valor de Frete:</label>
				<div class="col-md-9">
					<input type="text" name="valor_frete" class="form-control money" value="<?php echo $bairro->getValorFreteStr(); ?>" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="#localidade/bairro/<?php echo $bairro->getId(); ?>" class="btn btn-lg btn-default"><i class="fa fa-ban"></i> Cancelar</a>
					<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
				</div>
			</div>
		</form>
	</div>
</div>
