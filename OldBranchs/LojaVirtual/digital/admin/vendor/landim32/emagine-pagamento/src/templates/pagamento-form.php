<?php

namespace Emagine\Pagamento;

use Emagine\Base\EmagineApp;
use Emagine\Pagamento\Controls\PagamentoForm;
use Emagine\Pagamento\Model\DepositoInfo;
use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * @var EmagineApp $app
 * @var PagamentoForm $pagamentoForm
 * @var PagamentoInfo $pagamento
 * @var DepositoInfo[] $depositos
 */

?>
<input type="hidden" id="bandeira" name="bandeira" value="" />
<div class="form-group form-group-lg">
    <label class="col-md-3 control-label" for="bandeira">Pagamento:</label>
    <div class="col-md-9">
        <div class="btn-group btn-group-lg" data-toggle="buttons" id="forma_pagamento">
            <?php if ($pagamentoForm->getAceitaDebitoOnline() == true) : ?>
            <label class="<?php echo ($pagamento->getCodTipo() == PagamentoInfo::DEBITO_ONLINE) ? "btn active" : "btn" ?>">
                <input type="radio" id="forma_pagamento_debito" name="forma_pagamento" value="debito" <?php
                echo ($pagamento->getCodTipo() == PagamentoInfo::DEBITO_ONLINE) ? "checked='checked'" : ""
                ?>><i class="fa fa-credit-card-alt"></i> Débito
            </label>
            <?php endif; ?>
            <?php if ($pagamentoForm->getAceitaCreditoOnline() == true) : ?>
            <label class="<?php echo ($pagamento->getCodTipo() == PagamentoInfo::CREDITO_ONLINE) ? "btn active" : "btn" ?>">
                <input type="radio" id="forma_pagamento_credito" name="forma_pagamento" value="credito" <?php
                echo ($pagamento->getCodTipo() == PagamentoInfo::CREDITO_ONLINE) ? "checked='checked'" : ""
                ?>><i class="fa fa-credit-card"></i> Crédito
            </label>
            <?php endif; ?>
            <?php if ($pagamentoForm->getAceitaBoleto() == true) : ?>
            <label class="<?php echo ($pagamento->getCodTipo() == PagamentoInfo::BOLETO) ? "btn active" : "btn" ?>">
                <input type="radio" id="forma_pagamento_boleto" name="forma_pagamento" value="boleto" <?php
                echo ($pagamento->getCodTipo() == PagamentoInfo::BOLETO) ? "checked='checked'" : ""
                ?>><i class="fa fa-barcode"></i> Boleto
            </label>
            <?php endif; ?>
            <?php if ($pagamentoForm->getAceitaDinheiro() == true) : ?>
            <label class="<?php echo ($pagamento->getCodTipo() == PagamentoInfo::DINHEIRO) ? "btn active" : "btn" ?>">
                <input type="radio" id="forma_pagamento_dinheiro" name="forma_pagamento" value="dinheiro" <?php
                echo ($pagamento->getCodTipo() == PagamentoInfo::DINHEIRO) ? "checked='checked'" : ""
                ?>><i class="fa fa-money"></i> Dinheiro
            </label>
            <?php endif; ?>
            <?php if ($pagamentoForm->getAceitaCartaoOffline() == true) : ?>
            <label class="<?php echo ($pagamento->getCodTipo() == PagamentoInfo::CARTAO_OFFLINE) ? "btn active" : "btn" ?>">
                <input type="radio" id="forma_pagamento_cartao" name="forma_pagamento" value="cartao" <?php
                echo ($pagamento->getCodTipo() == PagamentoInfo::CARTAO_OFFLINE) ? "checked='checked'" : ""
                ?>><i class="fa fa-ticket"></i> Vale/Ticket
            </label>
            <?php endif; ?>
            <?php if ($pagamentoForm->getAceitaDeposito() == true) : ?>
                <label class="<?php echo ($pagamento->getCodTipo() == PagamentoInfo::DEPOSITO_BANCARIO) ? "btn active" : "btn" ?>">
                    <input type="radio" id="forma_pagamento_deposito" name="forma_pagamento" value="deposito" <?php
                    echo ($pagamento->getCodTipo() == PagamentoInfo::DEPOSITO_BANCARIO) ? "checked='checked'" : ""
                    ?>><i class="fa fa-bank"></i> Deposíto Bancário
                </label>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($pagamentoForm->getAceitaCreditoOnline() == true || $pagamentoForm->getAceitaDebitoOnline() == true) : ?>
<div id="credito"<?php echo (!($pagamento->getCodTipo() == PagamentoInfo::CREDITO_ONLINE || $pagamento->getCodTipo() == PagamentoInfo::DEBITO_ONLINE)) ? " style='display: none'" : ""; ?>>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="numero_cartao">Número:</label>
        <div class="col-md-8">
            <div class="input-group">
                <span id="bandeira_icone" class="input-group-addon"><i class="fa fa-fw fa-credit-card"></i></span>
                <input type="text" id="numero_cartao" name="numero_cartao" class="form-control masked"
                       placeholder="15 ou 16 digitos" maxlength="19"
                       value="<?php echo $pagamento->getNumeroCartao(); ?>" />
            </div>
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="nome_cartao">Nome no Cartão:</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-user-circle"></i></span>
                <input type="text" id="nome_cartao" name="nome_cartao" class="form-control" value="<?php echo $pagamento->getNomeCartao(); ?>" />
            </div>
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="data_expiracao">Validade:</label>
        <div class="col-md-3">
            <select id="data_expiracao" name="data_expiracao" class="form-control">
                <?php
                $data = strtotime(date("Y-m-01", time()));
                for ($i = 1; $i <= 60; $i++) {
                    $validadeValor = date("Y-m-01", $data);
                    $validadeTexto = date("m/Y", $data);
                    echo "<option value=\"$validadeValor\"";
                    if (strtotime($pagamento->getDataExpiracao()) == $data) {
                        echo " selected='selected'";
                    }
                    echo ">$validadeTexto</option>";
                    $data = strtotime(date("Y-m-d", $data) . " +1 month");
                }
                ?>
            </select>
        </div>
        <label class="col-md-2 control-label" for="codigo_seguranca">CVV:</label>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                <input type="text" id="codigo_seguranca" name="codigo_seguranca" class="form-control" maxlength="3" value="<?php echo $pagamento->getCVV(); ?>" />
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($pagamentoForm->getAceitaBoleto() == true) : ?>
<div id="boleto"<?php echo ($pagamento->getCodTipo() != PagamentoInfo::BOLETO) ? " style='display: none'" : ""; ?>>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="cpf">CPF*:</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-id-card"></i></span>
                <input type="text" id="cpf" name="cpf" class="form-control" value="<?php echo $pagamento->getCpf(); ?>" />
            </div>
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="cep">CEP*:</label>
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-map-marker"></i></span>
                <input type="text" id="cep" name="cep" class="form-control" maxlength="8" value="<?php echo $pagamento->getCep(); ?>" />
            </div>
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="logradouro">Endereço*:</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-map-marker"></i></span>
                <input type="text" id="logradouro" name="logradouro" class="form-control" value="<?php echo $pagamento->getLogradouro(); ?>" />
            </div>
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="bairro">Bairro*:</label>
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-map-marker"></i></span>
                <input type="text" id="bairro" name="bairro" class="form-control" value="<?php echo $pagamento->getBairro(); ?>" />
            </div>
        </div>
        <label class="col-md-1 control-label" for="numero">No*:</label>
        <div class="col-md-2">
            <input type="text" id="numero" name="numero" class="form-control" value="<?php echo $pagamento->getNumero(); ?>" />
        </div>
    </div>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="cidade">Cidade*:</label>
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-map-marker"></i></span>
                <input type="text" id="cidade" name="cidade" class="form-control" value="<?php echo $pagamento->getCidade(); ?>" />
            </div>
        </div>
        <label class="col-md-1 control-label" for="numero_cartao">UF*:</label>
        <div class="col-md-2">
            <input type="text" id="uf" name="uf" class="form-control" placeholder="UF" maxlength="2" style="text-transform: uppercase" value="<?php echo $pagamento->getUf(); ?>" />
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($pagamentoForm->getAceitaDinheiro() == true) : ?>
<div id="dinheiro"<?php echo ($pagamento->getCodTipo() != PagamentoInfo::DINHEIRO) ? " style='display: none'" : ""; ?>>
    <div class="form-group form-group-lg">
        <label class="col-md-4 control-label" for="troco_para">Troco para*:</label>
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-fw fa-money"></i></span>
                <input type="text" id="troco_para" name="troco_para" class="form-control money" value="<?php echo $pagamento->getTrocoPara(); ?>" />
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($pagamentoForm->getAceitaCartaoOffline() == true) : ?>
    <div id="cartao"<?php echo ($pagamento->getCodTipo() != PagamentoInfo::CARTAO_OFFLINE) ? " style='display: none'" : ""; ?>>
    </div>
<?php endif; ?>
<?php if ($pagamentoForm->getAceitaDeposito() == true) : ?>
    <div id="deposito"<?php echo ($pagamento->getCodTipo() != PagamentoInfo::DEPOSITO_BANCARIO) ? " style='display: none'" : ""; ?>>
        <div class="form-group form-group-lg">
            <label class="col-md-4 control-label" for="troco_para">Banco*:</label>
            <div class="col-md-3">
                <?php foreach ($depositos as $deposito) : ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="id_deposito" value="<?php
                            echo $deposito->getId(); ?>" <?php
                            echo ($pagamento->getIdDeposito() == $deposito->getId()) ? "checked='checked'" : "";
                            ?> /><?php echo $deposito->getNome(); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-5">
                <p>
                    <b>Pagamento:</b> O valor do pedido deve ser transferido ou depositado na conta que será informada na confirmação do pagamento.<br />
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>
