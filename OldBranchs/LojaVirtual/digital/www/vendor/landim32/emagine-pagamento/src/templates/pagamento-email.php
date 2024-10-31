<?php

namespace Emagine\Pagamento;

use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * @var string $assunto
 * @var PagamentoInfo $pagamento
 */

switch ($pagamento->getCodTipo()) {
    case PagamentoInfo::CREDITO_ONLINE:
        $corAviso = "#FF4841";
        break;
    case PagamentoInfo::DEBITO_ONLINE:
        $corAviso = "#7E7976";
        break;
    default:
        $corAviso = "#5ac15e";
        break;
}

?>
<html>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;max-width:600px;min-width:600px;border:0">
    <tbody>
    <tr>
        <td valign="top" style="background-color:#ffffff;background-image:none;background-repeat:no-repeat;padding-bottom:9px;background-size:cover;border-top:0;border-bottom:0;padding-top:9px;background-position:center" bgcolor="#ffffff">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                <tbody>
                <tr>
                    <td valign="top" style="padding-top:9px">
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;max-width:100%;min-width:100%" width="100%">
                            <tbody><tr>
                                <td valign="top" style="word-break:normal;color:#656565;line-height:150%;font-size:12px;font-family:Helvetica;padding-right:18px;text-align:center;padding-left:18px;padding-bottom:0px;padding-top:0" align="center">
                                    <h3 style="font-family:Arial,'Helvetica Neue',Helvetica,sans-serif;letter-spacing:normal;margin:0;padding:0;color:#4d4d4d;display:block;font-size:15px;font-style:normal;font-weight:bold;line-height:125%;text-align:center">
                                        <?php echo APP_NAME; ?>
                                    </h3>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" style="background-color:<?php echo $corAviso; ?>;background-image:none;background-repeat:no-repeat;padding-bottom:0;background-size:cover;border-top:0;border-bottom:0;padding-top:9px;background-position:center" bgcolor="#5ac15e">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;table-layout:fixed;min-width:100%">
                <tbody>
                <tr>
                    <td style="min-width:100%;padding:8px 18px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                            <tbody>
                            <tr>
                                <td>
                                    <span></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                <tbody>
                <tr>
                    <td valign="top" style="padding-top:0px">
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;max-width:100%;min-width:100%" width="100%">
                            <tbody>
                            <tr>
                                <td valign="top" style="word-break:normal;font-family:'Helvetica Neue',Helvetica,Arial,Verdana,sans-serif;font-size:16px;line-height:150%;padding:0px 18px 0px;color:#ffffff;text-align:center" align="center">
                                    <h1 style="font-size:26px;display:block;padding:0;color:#f8f8f8;font-family:'Helvetica Neue',Helvetica,Arial,Verdana,sans-serif;margin:0;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:normal;text-align:center"><?php echo $assunto; ?></h1>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;table-layout:fixed;min-width:100%">
                <tbody>
                <tr>
                    <td style="min-width:100%;padding:15px 18px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                            <tbody><tr>
                                <td>
                                    <span></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" style="background-color:#ffffff;background-image:none;background-repeat:no-repeat;padding-bottom:9px;background-size:cover;border-top:0;border-bottom:2px solid #eaeaea;padding-top:0;background-position:center" bgcolor="#ffffff">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                <tbody>
                <tr>
                    <td valign="top" style="padding-top:9px">
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;max-width:100%;min-width:100%" width="100%">
                            <tbody>
                            <tr>
                                <td valign="top" style="word-break:normal;text-align:left;line-height:150%;font-family:Arial;font-size:14px;color:#606060;padding-right:18px;padding-left:18px;padding-bottom:0px;padding-top:0" align="left">
                                    <p style="margin:10px 0;padding:0;color:#606060;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
                                        <?php $emailRetorno = "mailto:" . EMAIL_ADMIN . "?subject=" . urlencode("COMPROVANTE DO PEDIDO Nº #" . $pagamento->getId()); ?>
                                        <em>Olá <?php echo $pagamento->getUsuario()->getNome(); ?>,<br /><br />
                                            Após efetuar o depósito ou transferência pelo banco, encaminhe para o e-mail
                                            <a href="<?php echo $emailRetorno; ?>" style="color:#2baadf;font-weight:normal;text-decoration:underline"><?php echo EMAIL_ADMIN; ?></a>
                                            com o assunto <b>"COMPROVANTE DO PEDIDO Nº #<?php echo $pagamento->getId(); ?>"</b>
                                            uma foto ou print do comprovante de seu pagamento. O prazo só começa a contar
                                            a partir deste envio.<br />
                                            <b>Atenção:</b> Você deve realizar a transferência ou depósito em até 2 dias.
                                        </em>
                                    </p>
                                    <!--hr style="margin-top:18px;margin-bottom:18px;height:3px;border:0;border-bottom:1px solid #ddd">
                                    <p style="margin:10px 0;padding:0;color:#606060;font-family:Arial;font-size:14px;line-height:150%;text-align:left"></p-->
                                    <?php if ($pagamento->getCodTipo() == PagamentoInfo::DEPOSITO_BANCARIO) : ?>
                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:0;margin-right:0;width:100%;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;margin-bottom:16px" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                    <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;font-weight:normal;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        <strong>Segue abaixo dos dados para depósito ou transferência:</strong>
                                                    </p>
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;font-weight:normal;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        <span style="font-size:13px">
                                                            <?php $deposito = $pagamento->getDeposito() ?>
                                                            Banco: <b><?php echo $deposito->getBanco(); ?></b><br />
                                                            Agência: <b><?php echo $deposito->getAgencia(); ?></b><br />
                                                            Conta Corrente: <b><?php echo $deposito->getConta(); ?></b><br />
                                                            Favorecido: <b><?php echo $deposito->getCorrentista(); ?></b><br />
                                                            CPF/CNPJ: <b><?php echo $deposito->getCpfCnpj(); ?></b><br />
                                                            Valor: <b><?php echo $pagamento->getTotalStr(); ?></b>
                                                        </span>
                                                    </p>
                                                </span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <?php endif; ?>
                                    <?php /* if ($pedido->getCodSituacao() != PedidoInfo::CANCELADO) : ?>
                                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:0;margin-right:0;width:100%;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;margin-bottom:16px" width="100%">
                                            <tbody>
                                            <tr>
                                                <td style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                    <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
                                                        <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;font-weight:normal;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        <strong>O seu endereço de entrega:</strong>
                                                    </p>
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;font-weight:normal;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        <span style="color:#2baadf;font-weight:normal;text-decoration:underline">
                                                            <?php echo $pedido->getEnderecoCompleto(true, false); ?>
                                                        </span>
                                                    </p>
                                                </span>
                                                    <?php else : ?>
                                                        <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;font-weight:normal;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        <strong>Você deve retirar a sua compra em:</strong>
                                                    </p>
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;font-weight:normal;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        <span style="color:#2baadf;font-weight:normal;text-decoration:underline">
                                                            <?php echo $pedido->getLoja()->getEnderecoCompleto(); ?>
                                                        </span>
                                                    </p>
                                                </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    <?php endif; */ ?>
                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:0;margin-right:0;width:100%;margin-left:0;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;margin-bottom:16px" width="100%">
                                        <tbody>
                                        <tr>
                                            <td style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;font-weight:bold;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        Detalhes do Pedido
                                                    </p>
                                                </span>
                                            </td>
                                            <td style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;font-weight:bold;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;line-height:150%;color:#606060;font-family:Arial;text-align:right;font-size:14px">
                                                        Preço
                                                    </p>
                                                </span>
                                            </td>
                                            <td style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;font-weight:bold;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;line-height:150%;color:#606060;font-family:Arial;text-align:right;font-size:14px">
                                                        Qtde
                                                    </p>
                                                </span>
                                            </td>
                                            <td style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-bottom:0;font-weight:bold;padding-left:0;padding-bottom:0;padding-right:0;margin-top:0;margin-right:0;padding-top:0;margin-left:0;line-height:150%;color:#606060;font-family:Arial;text-align:right;font-size:14px">
                                                        Total
                                                    </p>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php $total = $pagamento->getValor(); ?>
                                        <?php foreach ($pagamento->listarItem() as $item) : ?>
                                            <tr>
                                                <td style="border-bottom:2px solid #ffffff;background:#f5f3f0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;padding-left:0;list-style-type:none">
                                                    <span style="display:block;margin-left:20px;margin-right:20px">
                                                        <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                            <?php echo $item->getDescricao(); ?>
                                                        </p>
                                                    </span>
                                                </td>
                                                <td style="background:#f5f3f0;font-size:14px;border-bottom:2px solid #ffffff;padding-left:0;list-style-type:none;padding-top:14px;padding-right:0;padding-bottom:14px;vertical-align:top;text-align:right" align="right" valign="top">
                                                    <span style="display:block;margin-left:20px;margin-right:20px">
                                                        <?php echo "R$&nbsp;" . number_format($item->getValor(), 2, ",", "."); ?>
                                                    </span>
                                                </td>
                                                <td style="background:#f5f3f0;font-size:14px;border-bottom:2px solid #ffffff;padding-left:0;list-style-type:none;padding-top:14px;padding-right:0;padding-bottom:14px;vertical-align:top;text-align:right" align="right" valign="top">
                                                    <span style="display:block;margin-left:20px;margin-right:20px">
                                                        <?php echo $item->getQuantidade(); ?>
                                                    </span>
                                                </td>
                                                <td style="background:#f5f3f0;font-size:14px;border-bottom:2px solid #ffffff;padding-left:0;list-style-type:none;padding-top:14px;padding-right:0;padding-bottom:14px;vertical-align:top;text-align:right" align="right" valign="top">
                                                    <span style="display:block;margin-left:20px;margin-right:20px">
                                                        <b style="color: #4c4c4c"><?php echo "R$&nbsp;" . number_format($total, 2, ",", "."); ?></b>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="3" style="border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;list-style-type:none;background:#ebe8e4">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                        Sub-Total:
                                                    </p>
                                                    <?php if ($pagamento->getTrocoPara() > 0) : ?>
                                                        <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                            Troco para:
                                                        </p>
                                                        <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;text-align:left;font-size:14px">
                                                            Troco:
                                                        </p>
                                                    <?php endif; ?>
                                                    <h4 style="display:block;text-align:left;padding:0;letter-spacing:normal;font-family:Arial;margin:0;font-style:normal;line-height:200%;font-weight:bold;color:#4c4c4c;font-size:16px">
                                                        Total:
                                                    </h4>
                                                </span>
                                            </td>
                                            <td style="list-style-type:none;border-bottom:2px solid #ffffff;padding-left:0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;background:#ebe8e4;text-align:right" align="right">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;font-size:14px;text-align:right">
                                                        R$&nbsp;<?php echo number_format($total, 2, ",", "."); ?>
                                                    </p>
                                                    <?php if ($pagamento->getTrocoPara() > 0) : ?>
                                                        <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;font-size:14px;text-align:right">
                                                            <?php echo number_format($pagamento->getTrocoPara(), 2, ",", "."); ?>
                                                        </p>
                                                        <p style="padding:0;margin:10px 0;margin-right:0;padding-left:0;padding-bottom:0;padding-right:0;padding-top:0;margin-top:0;margin-left:0;margin-bottom:0;line-height:150%;color:#606060;font-family:Arial;font-size:14px;text-align:right">
                                                            <?php echo $pagamento->getTrocoPara(); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <h4 style="margin:0;padding:0;font-family:Arial;display:block;font-style:normal;line-height:200%;letter-spacing:normal;font-size:16px;color:#4c4c4c;font-weight:bold;white-space:nowrap;text-align:right">
                                                        R$&nbsp;<?php echo number_format($total, 2, ",", "."); ?>
                                                    </h4>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="border-bottom:2px solid #ffffff;background:#f5f3f0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;padding-left:0;list-style-type:none">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    Método de pagamento:
                                                </span>
                                            </td>
                                            <td colspan="2" style="list-style-type:none;border-bottom:2px solid #ffffff;background:#f5f3f0;font-size:14px;padding-top:14px;padding-right:0;padding-bottom:14px;padding-left:0;text-align:right" align="right">
                                                <span style="display:block;margin-left:20px;margin-right:20px">
                                                    <?php echo $pagamento->getTipo(); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <p style="margin:10px 0;padding:0;color:#606060;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
                                        Se necessitar de ajuda ou tiver qualquer outra questão, por favor não hesite em nos contactar em
                                        <a href="mailto:<?php echo EMAIL_ADMIN; ?>" style="color:#2baadf;font-weight:normal;text-decoration:underline"><?php echo EMAIL_ADMIN; ?></a>.
                                    </p>
                                    <p style="margin:10px 0;padding:0;color:#606060;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
                                        Obrigado <?php echo $pagamento->getUsuario()->getNome(); ?>!
                                    </p>
                                    <p style="margin:10px 0;padding:0;color:#606060;font-family:Arial;font-size:14px;line-height:150%;text-align:left">
                                        Atenciosamente,
                                        <br>
                                        <?php echo APP_NAME; ?>
                                        <br>
                                        <span>
                                            <a href="<?php echo SITE_URL; ?>" style="color:#2baadf;font-weight:normal;text-decoration:underline" target="_blank">
                                                <?php echo SITE_URL; ?>
                                            </a><br>
                                            <a href="mailto:<?php echo EMAIL_ADMIN; ?>" style="color:#2baadf;font-weight:normal;text-decoration:underline" target="_blank"><?php echo EMAIL_ADMIN; ?></a>
                                        </span>
                                    </p>
                                    <hr style="margin-top:18px;margin-bottom:18px;height:3px;border:0;border-bottom:1px solid #ddd">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;table-layout:fixed;min-width:100%">
                <tbody>
                <tr>
                    <td style="min-width:100%;padding:5px 18px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                            <tbody>
                            <tr>
                                <td>
                                    <span></span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" style="background-color:#f0efed;background-image:none;background-repeat:no-repeat;padding-bottom:9px;background-size:cover;border-top:0;border-bottom:0;padding-top:9px;background-position:center" bgcolor="#f0efed">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;min-width:100%">
                <tbody>
                <tr>
                    <td valign="top" style="padding-top:9px">
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;max-width:100%;min-width:100%" width="100%">
                            <tbody>
                            <tr>
                                <td valign="top" style="word-break:normal;text-align:center;line-height:150%;font-family:Helvetica;font-size:12px;color:#656565;padding-right:18px;padding-left:18px;padding-bottom:9px;padding-top:0" align="center">Direitos reservados::<br>
                                    <a href="<?php echo SITE_URL; ?>" style="color:#2baadf;font-weight:normal;text-decoration:underline" target="_blank"><?php echo SITE_URL; ?></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>