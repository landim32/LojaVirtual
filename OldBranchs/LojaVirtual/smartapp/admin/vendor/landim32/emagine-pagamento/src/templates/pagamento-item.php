<?php
namespace Emagine\Login;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * @var EmagineApp $app
 * @var PagamentoInfo $pagamento
 */

$usuario = $pagamento->getUsuario();

?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <i class="fa fa-5x fa-dollar"></i>
                        </div>
                        <div class="col-md-9">
                            <h2>Pagamento #<?php echo $pagamento->getId(); ?></h2>
                            <div>
                                <span class="badge"><?php echo $pagamento->getTipo(); ?></span>
                                <span class="<?php echo $pagamento->getSituacaoClasse(); ?>">
                                    <?php echo $pagamento->getSituacao(); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <?php if (!isNullOrEmpty($pagamento->getMensagem())) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-warning"></i> <?php echo $pagamento->getMensagem(); ?>
                        </div>
                    <?php endif; ?>
                    <dl class="dl-horizontal">
                        <dt>Nome:</dt>
                        <dd><?php echo $usuario->getNome(); ?></dd>
                        <?php if (!isNullOrEmpty($usuario->getCpfCnpj())) : ?>
                            <dt>CPF/CNPJ:</dt>
                            <dd><?php echo $usuario->getCpfCnpjStr(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($usuario->getEmail())) : ?>
                            <dt>Email:</dt>
                            <dd>
                                <a href="mailto:<?php echo $usuario->getEmail(); ?>">
                                    <i class="fa fa-envelope"></i> <?php echo $usuario->getEmail(); ?>
                                </a>
                            </dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($usuario->getTelefone())) : ?>
                            <dt>Telefone:</dt>
                            <dd><?php echo $usuario->getTelefoneStr(); ?></dd>
                        <?php endif; ?>
                    </dl>
                    <hr />
                    <dl class="dl-horizontal">
                        <?php if (strtotime($pagamento->getDataInclusao()) > 0) : ?>
                            <dt>Data de Inclusão:</dt>
                            <dd><?php echo date("d/m/Y H:i", strtotime($pagamento->getDataInclusao())); ?></dd>
                        <?php endif; ?>
                        <?php if (strtotime($pagamento->getUltimaAlteracao()) > 0) : ?>
                            <dt>Última Alteração:</dt>
                            <dd><?php echo date("d/m/Y H:i", strtotime($pagamento->getUltimaAlteracao())); ?></dd>
                        <?php endif; ?>
                        <?php if (strtotime($pagamento->getDataVencimento()) > 0) : ?>
                            <dt>Vencimento:</dt>
                            <dd><?php echo $pagamento->getDataVencimentoStr(); ?></dd>
                        <?php endif; ?>
                        <?php if (strtotime($pagamento->getDataPagamento()) > 0) : ?>
                            <dt>Data de Pagamento:</dt>
                            <dd><?php echo $pagamento->getDataPagamentoStr(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getLogradouro())) : ?>
                            <dt>Logradouro:</dt>
                            <dd><?php echo $pagamento->getLogradouro(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getNumero())) : ?>
                            <dt>Número:</dt>
                            <dd><?php echo $pagamento->getNumero(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getBairro())) : ?>
                            <dt>Bairro:</dt>
                            <dd><?php echo $pagamento->getBairro(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getCidade())) : ?>
                            <dt>Cidade:</dt>
                            <dd><?php echo $pagamento->getCidade(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getUf())) : ?>
                            <dt>UF:</dt>
                            <dd><?php echo $pagamento->getUf(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getCep())) : ?>
                            <dt>CEP:</dt>
                            <dd><?php echo $pagamento->getCep(); ?></dd>
                        <?php endif; ?>
                        <?php if (!isNullOrEmpty($pagamento->getObservacao())) : ?>
                            <dt>Observação:</dt>
                            <dd><?php echo $pagamento->getObservacao(); ?></dd>
                        <?php endif; ?>
                    </dl>

                </div>
            </div>
            <?php if (count($pagamento->listarItem()) > 0) : ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-right">Quantidade</th>
                                <th class="text-right">Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pagamento->listarItem() as $item) : ?>
                                <tr>
                                    <td><?php echo $item->getDescricao(); ?></td>
                                    <td class="text-right"><?php echo $item->getQuantidade(); ?></td>
                                    <td class="text-right"><?php echo "R$ " . number_format($item->getValor(), 2, ",", "."); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <?php if ($pagamento->getValor() > 0) : ?>
                                <tr>
                                    <th colspan="2" class="text-right">Valor:</th>
                                    <td class="text-right"><?php echo $pagamento->getValorStr(); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($pagamento->getValorMulta() > 0) : ?>
                                <tr>
                                    <th colspan="2" class="text-right">Multa:</th>
                                    <td class="text-right"><?php echo number_format($pagamento->getValorMulta(), 2, ",", "."); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($pagamento->getValorJuro() > 0) : ?>
                                <tr>
                                    <th colspan="2" class="text-right">Juro:</th>
                                    <td class="text-right"><?php echo number_format($pagamento->getValorJuro(), 2, ",", "."); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($pagamento->getValorDesconto() > 0) : ?>
                                <tr>
                                    <th colspan="2" class="text-right">Desconto:</th>
                                    <td class="text-right"><?php echo number_format($pagamento->getValorDesconto(), 2, ",", "."); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($pagamento->getTotal() > 0) : ?>
                                <tr>
                                    <th colspan="2" class="text-right">Total:</th>
                                    <td class="text-right"><?php echo $pagamento->getTotalStr(); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($pagamento->getTrocoPara() > 0) : ?>
                                <tr>
                                    <th colspan="2" class="text-right">Troco Para:</th>
                                    <td class="text-right"><?php echo "R$ " . number_format($pagamento->getTrocoPara(), 2, ",", "."); ?></td>
                                </tr>
                            <?php endif; ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (count($pagamento->listarOpcao()) > 0) : ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr>
                                <th>Opção</th>
                                <th>Valor</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pagamento->listarOpcao() as $opcao) : ?>
                                <tr>
                                    <td><?php echo $opcao->getChave(); ?></td>
                                    <td><?php echo $opcao->getValor(); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-3" style="padding-top: 40px;">
            <a href="<?php echo $app->getBaseUrl() . "/pagamento/excluir/" . $pagamento->getId(); ?>" class="confirm">
                <i class="fa fa-fw fa-trash"></i> Excluir
            </a><br />
            <?php if (!isNullOrEmpty($pagamento->getBoletoUrl())) : ?>
                <a href="<?php echo $pagamento->getBoletoUrl(); ?>">
                    <i class="fa fa-fw fa-print"></i> Imprimir Boleto
                </a><br />
            <?php endif; ?>
            <?php if (!isNullOrEmpty($pagamento->getAutenticacaoUrl())) : ?>
                <a href="<?php echo $pagamento->getAutenticacaoUrl(); ?>">
                    <i class="fa fa-fw fa-credit-card"></i> Autenticar
                </a><br />
            <?php endif; ?>
        </div>
    </div>
</div>
