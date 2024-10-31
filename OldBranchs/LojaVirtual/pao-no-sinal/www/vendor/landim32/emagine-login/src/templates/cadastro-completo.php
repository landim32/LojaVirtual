<?php
namespace Emagine\Login;

use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\GrupoBLL;
use Emagine\Endereco\BLL\UfBLL;
use Emagine\Login\Factory\GrupoFactory;
use Emagine\Login\Factory\UsuarioFactory;
use Emagine\Login\Model\UsuarioInfo;

/**
 * @var UsuarioInfo $usuario
 * @var string $error
 * @var string $urlVoltar
 * @var string $textoVoltar
 * @var string $textoGravar
 */

//$regraUsuario = UsuarioFactory::create();
//$regraGrupo = GrupoFactory::create();
$regraUf = new UfBLL();

$estados = $regraUf->listar();
 ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!--div class="col-md-10 col-md-offset-1"-->
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="post" class="form-vertical">
                        <?php if ($usuario->getId() > 0) : ?>
                        <input type="hidden" name="id_usuario" value="<?php echo $usuario->getId(); ?>" />
                        <?php endif; ?>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-warning"></i> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-group-lg">
                                    <label class="control-label">Nome(*):</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                                        <input type="text" name="nome" class="form-control" value="<?php echo $usuario->getNome(); ?>" />
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="control-label">Email(*):</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                                        <input type="text" name="email" class="form-control" value="<?php echo $usuario->getEmail(); ?>" />
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 15px">
                                    <div class="col-md-6">
                                        <label class="control-label">Telefone:</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="telefone" class="form-control" value="<?php echo $usuario->getTelefone(); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Celular:</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon"><i class="fa fa-mobile-phone"></i></span>
                                            <input type="text" name="celular" class="form-control" value="<?php echo $usuario->getTelefone(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <?php if (!($usuario->getId() > 0)) : ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Senha(*):</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" name="senha" class="form-control" placeholder="Preencha a senha" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label">Confirma senha(*):</label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                            <input type="password" name="confirma" class="form-control" placeholder="Confirme sua senha" />
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-group-lg">
                                            <label class="control-label">CEP:</label>
                                            <input type="text" name="cep" class="form-control" value="<?php echo $usuario->getCep(); ?>" placeholder="Números" maxlength="8" />
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group form-group-lg">
                                            <label class="control-label">Logradouro<?php echo (UsuarioBLL::getEnderecoObrigatorio() == true) ? "(*)" : ""; ?>:</label>
                                            <input type="text" name="logradouro" class="form-control" value="<?php echo $usuario->getLogradouro(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group form-group-lg">
                                            <label class="control-label">Complemento:</label>
                                            <input type="text" name="complemento" class="form-control" value="<?php echo $usuario->getComplemento(); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-group-lg">
                                            <label class="control-label">Número:</label>
                                            <input type="text" name="numero" class="form-control" value="<?php echo $usuario->getNumero(); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group form-group-lg">
                                            <label class="control-label" for="uf">UF<?php echo (UsuarioBLL::getEnderecoObrigatorio() == true) ? "(*)" : ""; ?>:</label>
                                            <?php if (UsuarioBLL::getUfLivre() == true) : ?>
                                                <input type="text" id="uf" name="uf" class="form-control" maxlength="2" style="text-transform: uppercase;" />
                                            <?php else : ?>
                                                <select id="uf" name="uf" class="form-control uf-select">
                                                    <option value="">--selecione--</option>
                                                    <?php foreach ($estados as $estado) : ?>
                                                        <option value="<?php echo $estado->getUf(); ?>"><?php echo $estado->getUf(); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group form-group-lg">
                                            <label class="control-label" for="cidade">Cidade<?php echo (UsuarioBLL::getEnderecoObrigatorio() == true) ? "(*)" : ""; ?>:</label>
                                            <?php if (UsuarioBLL::getCidadeLivre() == true) : ?>
                                                <input type="text" name="cidade" class="form-control" />
                                            <?php else : ?>
                                                <select id="cidade" name="id_cidade" class="form-control cidade-select">
                                                    <option value="">Carregando...</option>
                                                </select>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group-lg">
                                    <label class="control-label" for="bairro">Bairro<?php echo (UsuarioBLL::getEnderecoObrigatorio() == true) ? "(*)" : ""; ?>:</label>
                                    <?php if (UsuarioBLL::getBairroLivre() == true) : ?>
                                        <input type="text" id="bairro" name="bairro" class="form-control" />
                                    <?php else : ?>
                                        <select id="bairro" id="bairro" name="id_bairro" class="form-control bairro-select">
                                            <option value="">--selecione--</option>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <?php if (!isNullOrEmpty($urlVoltar)) : ?>
                                    <a href="<?php echo $urlVoltar; ?>" class="btn btn-lg btn-default"><?php echo $textoVoltar; ?></a>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-lg btn-primary"><?php echo $textoGravar; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
