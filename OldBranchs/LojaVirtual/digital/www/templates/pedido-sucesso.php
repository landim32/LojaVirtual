<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var PedidoInfo $pedido
 * @var PagamentoInfo $pagamento
 */
$urlBase = $app->getBaseUrl() . "/" . $loja->getSlug();
?>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="stepwizard">
                <div class="stepwizard-row setup-panel">
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">1</button>
                        <p>Cadastro</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">2</button>
                        <p>Carrinho</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">3</button>
                        <p>Entrega</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-default btn-circle" disabled="disabled">4</button>
                        <p>Pagamento</p>
                    </div>
                    <div class="stepwizard-step">
                        <button type="button" class="btn btn-primary btn-circle" disabled="disabled">5</button>
                        <p>Finalizar</p>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <?php if ($pedido->getCodSituacao() == PedidoInfo::PENDENTE) : ?>
                    <i class="fa fa-5x fa-thumbs-up text-success"></i>
                    <h1>Seu pedido foi realizado com sucesso!</h1>
                    <br />
                    <p>
                        <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
                        Seu pedido foi realizado com sucesso e está sendo entregue no endereço que você selecionou.
                        <?php else : ?>
                        Seu pedido foi realizado com sucesso e está aguardando ser retirado na loja.
                        <?php endif; ?>
                    </p>
                    <br />
                    <a href="<?php echo $urlBase; ?>" class="btn btn-lg btn-primary">
                        <i class="fa fa-chevron-left"></i> Voltar
                    </a>
                <?php elseif ($pedido->getCodSituacao() == PedidoInfo::AGUARDANDO_PAGAMENTO) : ?>
                    <?php if ($pagamento->getCodTipo() == PagamentoInfo::BOLETO) : ?>
                        <i class="fa fa-5x fa-clock-o text-info"></i>
                        <h1>Seu pedido ainda não foi finalizado!</h1>
                        <br />
                        <p>
                            Seu pedido foi feito mais ainda está aguardando o pagamento do boleto bancário.
                            Esse pagamento pode demorar até 2 (dois) dias úteis para ser contabilizado.
                            <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
                                Assim que for contabilizado, seu pedido será enviado para entrega.
                            <?php else : ?>
                                Assim que for contabilizado, você poderá retira-lo em nossa loja.
                            <?php endif; ?>
                        </p>
                        <p>
                            Clique no botão abaixo para imprimir o boleto. Pode demorar de um a dois dias para baixa no pagamento.
                        </p>
                        <br />
                        <a href="<?php echo $pagamento->getBoletoUrl(); ?>" target="_blank" class="btn btn-lg btn-primary">
                            <i class="fa fa-print"></i> Imprimir Boleto
                        </a>
                        <a href="<?php echo $urlBase; ?>" class="btn btn-lg btn-default">
                            <i class="fa fa-chevron-left"></i> Voltar
                        </a>
                    <?php else : ?>
                        <?php $formaPgtos = array(PedidoInfo::DINHEIRO, PedidoInfo::CARTAO_OFFLINE); ?>
                        <?php if (in_array($pedido->getCodPagamento(), $formaPgtos)) : ?>
                            <i class="fa fa-5x fa-thumbs-up text-success"></i>
                            <h1>Seu pedido foi realizado com sucesso!</h1>
                            <br />
                            <p>
                                <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
                                    Seu pedido foi realizado com sucesso e está sendo entregue no endereço que você selecionou.
                                <?php else : ?>
                                    Seu pedido foi realizado com sucesso e está aguardando ser retirado na loja.
                                <?php endif; ?>
                            </p>
                        <?php else : ?>
                            <i class="fa fa-5x fa-clock-o text-info"></i>
                            <h1>Seu pagamento ainda não foi efetuado!</h1>
                            <br />
                            <p>
                                O pagamento do seu pedido ainda não foi confirmando.
                                <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
                                    Assim que for confirmado será entregue no endereço que você selecionou.
                                <?php else : ?>
                                    Assim que for confirmado estará aguardando ser retirado na loja.
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <br />
                        <a href="<?php echo $urlBase; ?>" class="btn btn-lg btn-default">
                            <i class="fa fa-chevron-left"></i> Voltar
                        </a>
                    <?php endif; ?>
                <?php else : ?>
                    <i class="fa fa-5x fa-remove text-danger"></i>
                    <h1>
                        <?php if (!isNullOrEmpty($pagamento->getMensagem())) : ?>
                        <?php echo $pagamento->getMensagem(); ?>
                        <?php else : ?>
                        O pedido encontra-se em situação "<?php echo $pedido->getSituacaoStr(); ?>"
                        <?php endif; ?>
                    </h1>
                    <br />
                    <p>
                        O pedido encontra-se em situação <strong><?php echo $pedido->getSituacaoStr(); ?></strong>
                        <?php if ($pedido->getCodEntrega() == PedidoInfo::ENTREGAR) : ?>
                            e está programado para ser entregue.
                        <?php else : ?>
                            e está aguardando a sua retirada na loja.
                        <?php endif; ?>
                    </p>
                    <br />
                    <a href="<?php echo $urlBase; ?>" class="btn btn-lg btn-primary">
                        <i class="fa fa-chevron-left"></i> Voltar
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
