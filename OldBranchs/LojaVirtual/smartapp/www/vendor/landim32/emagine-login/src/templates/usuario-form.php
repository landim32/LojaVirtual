<?php

namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\GrupoBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\GrupoInfo;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var GrupoInfo[]|null $grupos
 * @var string[]|null $situacoes
 * @var string @erro
 */

?>
<input type="hidden" name="id_usuario" value="<?php echo $usuario->getId(); ?>" />
<?php if (isset($erro)) : ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <i class="fa fa-warning"></i> <?php echo $erro; ?>
    </div>
<?php endif; ?>
<div class="form-group">
    <label class="control-label col-md-3" for="nome">Nome:</label>
    <div class="col-md-9">
        <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $usuario->getNome(); ?>" />
    </div>
</div>
<?php if (count($grupos) > 0) : ?>
<div class="form-group">
    <label class="control-label col-md-3" for="grupos">Grupos:</label>
    <div class="col-md-9">
        <select id="grupos" name="grupos[]" class="form-control multiselect" multiple="multiple">
            <?php foreach ($grupos as $grupo) : ?>
                <option value="<?php echo $grupo->getId()?>"<?php
                echo $usuario->temGrupo($grupo->getId()) ? " selected='selected'" : "";
                ?>><?php echo $grupo->getNome()?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php endif; ?>
<div class="form-group">
    <label class="control-label col-md-3" for="cpf_cnpj">CPF/CNPJ:</label>
    <div class="col-md-9">
        <input type="text" id="cpf_cnpj" name="cpf_cnpj" class="form-control" value="<?php echo $usuario->getCpfCnpj(); ?>" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3" for="email">Email:</label>
    <div class="col-md-9">
        <input type="text" id="email" name="email" class="form-control" value="<?php echo $usuario->getEmail(); ?>" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-md-3" for="telefone">Telefone:</label>
    <div class="col-md-4">
        <input type="text" id="telefone" name="telefone" class="form-control" value="<?php echo $usuario->getTelefone(); ?>" />
    </div>
</div>
<?php if (!($usuario->getId() > 0)) : ?>
    <div class="form-group">
        <label class="control-label col-md-3" for="senha">Senha:</label>
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="password" name="senha" class="form-control" placeholder="Preencha a Senha" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3" for="senha">Confirmar:</label>
        <div class="col-md-9">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="password" name="confirma" class="form-control" placeholder="Confirme sua senha" />
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (count($situacoes) > 0) : ?>
<div class="form-group">
    <label class="control-label col-md-3" for="cod_situacao">Situação:</label>
    <div class="col-md-6">
        <select id="cod_situacao" name="cod_situacao" class="form-control">
            <?php foreach ($situacoes as $key => $value) : ?>
                <option value="<?php echo $key; ?>"<?php echo ($key == $usuario->getCodSituacao()) ? " selected='selected'" : ""  ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php endif; ?>