<?php
use Emagine\Endereco\Model\PaisInfo;
use Emagine\Endereco\Model\UfInfo;
/**
 * @var PaisInfo[] $paises
 * @var UfInfo $uf;
 */
 ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-reorder"></i> Estados</h3>
	</div>
	<div class="panel-body">
		<form method="post" action="<?php echo $app->getBaseUrl(); ?>/localidade/uf" class="form-horizontal form-ajax">
			<div class="form-group text-right">
				<label class="col-md-2 control-label">Uf:</label>
				<div class="col-md-2">
					<input type="text" name="uf" class="form-control" value="<?php echo $uf->getUf(); ?>" maxlength="2" style="text-transform: uppercase" />
				</div>
                <label class="col-md-2 control-label">Nome:</label>
                <div class="col-md-6">
                    <input type="text" name="nome" class="form-control" value="<?php echo $uf->getNome(); ?>" />
                </div>
			</div>
			<div class="form-group">
				<label class="col-md-2 control-label">País:</label>
				<div class="col-md-10">
                    <select name="id_pais" class="form-control">
                        <?php foreach ($paises as $item) : ?>
                            <option value="<?php echo $item->getId(); ?>"<?php echo ($item->getId() == $uf->getIdPais()) ? " selected='selected'" : ""  ?>><?php echo $item->getNome(); ?></option>
                        <?php endforeach; ?>
                    </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
				</div>
			</div>
		</form>
	</div>
</div>
