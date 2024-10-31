<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\SeguimentoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var SeguimentoInfo[] $seguimentos
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
 */

$regraLoja = new LojaBLL();

if ($loja->getId() > 0) {
    $urlVoltar = $app->getBaseUrl() . "/loja/" . $loja->getSlug();
}
else {
    $urlVoltar = $app->getBaseUrl() . "/lojas";
}

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-suitcase"></i> Alterar dados da Loja</h3>
                </div>
                <div class="panel-body">
                    <?php if (!StringUtils::isNullOrEmpty($erro)) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $erro; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/loja"; ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <input type="hidden" name="id_loja" value="<?php echo $loja->getId(); ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label">Foto:</label>
                            <div class="col-md-9">
                                <input type="file" name="foto" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="nome">Nome:</label>
                            <div class="col-md-9">
                                <input type="text" id="nome" name="nome" class="form-control" value="<?php echo $loja->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="id_seguimento">Seguimento:</label>
                            <div class="col-md-9">
                                <select id="id_seguimento" name="id_seguimento" class="form-control">
                                    <option value="">--Nenhum--</option>
                                    <?php foreach ($seguimentos as $seguimento) : ?>
                                        <option value="<?php echo $seguimento->getId(); ?>"<?php
                                        echo ($seguimento->getId() == $loja->getIdSeguimento()) ? " selected='selected'" : "";
                                        ?>><?php echo $seguimento->getNome(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="email">Email:</label>
                            <div class="col-md-9">
                                <input type="text" id="email" name="email" class="form-control" value="<?php echo $loja->getEmail(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="cnpj">CNPJ:</label>
                            <div class="col-md-9">
                                <input type="text" id="cnpj" name="cnpj" class="form-control" value="<?php echo $loja->getCnpj(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="cep">CEP:</label>
                            <div class="col-md-3">
                                <input type="text" id="cep" name="cep" class="form-control cep-busca" value="<?php echo $loja->getCep(); ?>" maxlength="8" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="logradouro">Logradouro:</label>
                            <div class="col-md-9">
                                <input type="text" id="logradouro" name="logradouro" class="form-control cep-logradouro" value="<?php echo $loja->getLogradouro(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="complemento">Complemento:</label>
                            <div class="col-md-5">
                                <input type="text" id="complemento" name="complemento" class="form-control cep-complemento" value="<?php echo $loja->getComplemento(); ?>" />
                            </div>
                            <label class="col-md-2 control-label" for="numero">Nº:</label>
                            <div class="col-md-2">
                                <input type="text" id="numero" name="numero" class="form-control cep-numero" value="<?php echo $loja->getNumero(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="bairro">Bairro:</label>
                            <div class="col-md-9">
                                <input type="text" id="bairro" name="bairro" class="form-control cep-bairro" value="<?php echo $loja->getBairro(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="cidade">Cidade:</label>
                            <div class="col-md-6">
                                <input type="text" id="cidade" name="cidade" class="form-control cep-cidade" value="<?php echo $loja->getCidade(); ?>" />
                            </div>
                            <label class="col-md-1 control-label" for="uf">UF:</label>
                            <div class="col-md-2">
                                <input type="text" id="uf" name="uf" class="form-control cep-uf"
                                       value="<?php echo $loja->getUf(); ?>" maxlength="2" style="text-transform: uppercase" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="latitude">Latitude<span class="required">*</span>:</label>
                            <div class="col-md-3">
                                <input type="text" id="latitude" name="latitude" class="form-control cep-latitude" value="<?php echo $loja->getLatitude(); ?>" required="required" />
                            </div>
                            <label class="col-md-3 control-label" for="longitude">Longitude<span class="required">*</span>:</label>
                            <div class="col-md-3">
                                <input type="text" id="longitude" name="longitude" class="form-control cep-longitude" value="<?php echo $loja->getLongitude(); ?>" required="required" />
                            </div>
                        </div>
                        <?php /* if (LojaBLL::usaPagamentoOnline()) : ?>
                            <div class="form-group text-right">
                                <label class="col-md-3 control-label" for="cod_gateway">Gateway de Pgto:</label>
                                <div class="col-md-5">
                                    <select id="cod_gateway" name="cod_gateway" class="form-control">
                                        <option value="">--nenhum--</option>
                                        <?php foreach ($regraLoja->listarGateway() as $key => $value) : ?>
                                            <option value="<?php echo $key; ?>"<?php
                                            echo ($key == $loja->getCodGateway()) ? " selected='selected'" : ""  ?>><?php echo $value;
                                                ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php else : ?>
                            <input type="hidden" name="cod_gateway" value="" />
                        <?php endif; */ ?>
                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="controle_estoque" value="1" <?php
                                    echo ($loja->getControleEstoque() == true) ? "checked=\"checked\"" : "";
                                    ?>  />
                                    Usa controle de estoque
                                </label><br />
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="usa_retirar" value="1" <?php
                                    echo ($loja->getUsaRetirar() == true) ? "checked=\"checked\"" : "";
                                    ?> />
                                    Aceita retirada de produtos na loja
                                </label><br />
                                <?php if (LojaBLL::usaRetiradaMapeada()) : ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="usa_retirada_mapeada" value="1" <?php
                                        echo ($loja->getUsaRetiradaMapeada() == true) ? "checked=\"checked\"" : "";
                                        ?> />
                                        Aceita retirada acompanhada pelo mapa
                                    </label><br />
                                <?php else : ?>
                                    <input type="hidden" name="usa_retirada_mapeada" value="0" />
                                <?php endif; ?>
                                <?php if (LojaBLL::usaPagamentoOnline()) : ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="usa_gateway" value="1" <?php
                                        echo ($loja->getUsaGateway() == true) ? "checked=\"checked\"" : "";
                                        ?>  />
                                        Usa gateways de pagamento
                                    </label><br />
                                <?php else : ?>
                                    <input type="hidden" name="usa_gateway" value="0" />
                                <?php endif; ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="usa_entregar" value="1" <?php
                                    echo ($loja->getUsaEntregar() == true) ? "checked=\"checked\"" : "";
                                    ?> />
                                    A loja realiza entregas
                                </label><br />
                                <?php if (LojaBLL::usaPagamentoOnline()) : ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="aceita_credito_online" value="1" <?php
                                        echo ($loja->getAceitaCreditoOnline() == true) ? "checked=\"checked\"" : "";
                                        ?> />
                                        Aceita pagamento por cartão de crédito online
                                    </label><br />
                                    <?php if (LojaBLL::usaDebitoOnline() == true) : ?>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="aceita_debito_online" value="1" <?php
                                            echo ($loja->getAceitaDebitoOnline() == true) ? "checked=\"checked\"" : "";
                                            ?>  />
                                            Aceita pagamento por cartão de débito online
                                        </label><br />
                                    <?php endif; ?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="aceita_boleto" value="1" <?php
                                        echo ($loja->getAceitaBoleto() == true) ? "checked=\"checked\"" : "";
                                        ?> />
                                        Aceita pagamento por boleto bancário
                                    </label><br />
                                <?php else : ?>
                                    <input type="hidden" name="aceita_credito_online" value="0" />
                                    <input type="hidden" name="aceita_debito_online" value="0" />
                                    <input type="hidden" name="aceita_boleto" value="0" />
                                <?php endif; ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="aceita_dinheiro" value="1" <?php
                                    echo ($loja->getAceitaDinheiro() == true) ? "checked=\"checked\"" : "";
                                    ?> />
                                    Aceita pagamento em dinheiro
                                </label><br />
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="aceita_cartao_offline" value="1" <?php
                                    echo ($loja->getAceitaCartaoOffline() == true) ? "checked=\"checked\"" : "";
                                    ?> />
                                    Aceita Vale/Ticket/Cartão na Entrega
                                </label>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="estoque_minimo">Estoque Mínimo:</label>
                            <div class="col-md-3">
                                <input type="number" id="estoque_minimo" name="estoque_minimo" class="form-control" value="<?php echo $loja->getEstoqueMinimo(); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="valor_minimo">Valor Mínimo:</label>
                            <div class="col-md-3">
                                <input type="text" id="valor_minimo" name="valor_minimo" class="form-control money"
                                       value="<?php echo number_format($loja->getValorMinimo(), 2, ",", ""); ?>" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="descricao">Descrição:</label>
                            <div class="col-md-9">
                                <textarea id="descricao" name="descricao" class="form-control" rows="5"><?php echo $loja->getDescricao(); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="mensagem_retirada">Mensagem a quem vai retirar:</label>
                            <div class="col-md-9">
                                <textarea id="mensagem_retirada" name="mensagem_retirada" class="form-control" rows="5"><?php echo $loja->getMensagemRetirada(); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="cod_situacao">Situação:</label>
                            <div class="col-md-5">
                                <select id="cod_situacao" name="cod_situacao" class="form-control">
                                    <?php $lista = $regraLoja->listarSituacao(); ?>
                                    <?php foreach ($lista as $key => $value) : ?>
                                        <option value="<?php echo $key; ?>"<?php echo ($key == $loja->getCodSituacao()) ? " selected='selected'" : ""  ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $urlVoltar; ?>" class="btn btn-lg btn-default">
                                    <i class="fa fa-chevron-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>