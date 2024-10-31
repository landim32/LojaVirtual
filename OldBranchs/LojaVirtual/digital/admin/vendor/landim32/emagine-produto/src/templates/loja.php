<?php

namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var LojaInfo[] $lojasCopia
 * @var UsuarioInfo $usuario
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var string $erro
 * @var string $sucesso
 */
?>
<?php require __DIR__ . "/usuario-modal.php"; ?>
<?php require __DIR__ . "/loja-modal.php"; ?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div id="main-content">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php if (!isNullOrEmpty($erro)) : ?>
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-warning"></i> <?php echo $erro; ?>
                                    </div>
                                <?php elseif (!isNullOrEmpty($sucesso)) : ?>
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <i class="fa fa-question-circle"></i> <?php echo $sucesso; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <?php if (!isNullOrEmpty($loja->getFoto())) : ?>
                                            <img src="<?php echo $app->getBaseUrl() . "/loja/120x80/" . $loja->getFoto(); ?>" class="img-responsive" />
                                        <?php else : ?>
                                            <i class="fa fa-suitcase fa-5x"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-9">
                                        <h2 style="margin-top: 5px; margin-bottom: 2px"><?php echo $loja->getNome(); ?></h2>
                                        <?php if (!isNullOrEmpty($loja->getEmail())) : ?>
                                            <a href="mailto:<?php echo $loja->getEmail(); ?>">
                                                <i class="fa fa-envelope"></i> <?php echo $loja->getEmail(); ?>
                                            </a>
                                        <?php endif; ?><br />
                                        <span class="<?php echo $loja->getSituacaoClasse(); ?>">
                                            <?php echo $loja->getSituacaoStr(); ?>
                                        </span>
                                    </div>
                                </div>
                                <hr />
                                <dl class="dl-horizontal">
                                    <?php if (!isNullOrEmpty($loja->getNomeSeguimento())) : ?>
                                        <dt>Seguimento:</dt>
                                        <dd><?php echo $loja->getNomeSeguimento(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getCnpj())) : ?>
                                        <dt>CNPJ:</dt>
                                        <dd><?php echo $loja->getCnpjStr(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getLogradouro())) : ?>
                                    <dt>Logradouro:</dt>
                                    <dd><?php echo $loja->getLogradouro(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getComplemento())) : ?>
                                    <dt>Complemento:</dt>
                                    <dd><?php
                                        echo $loja->getComplemento();
                                        if (!isNullOrEmpty($loja->getNumero())) {
                                            echo ", " . $loja->getNumero();
                                        }
                                        ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getBairro())) : ?>
                                        <dt>Bairro:</dt>
                                        <dd><?php echo $loja->getBairro(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getCidade())) : ?>
                                        <dt>Cidade:</dt>
                                        <dd><?php echo $loja->getCidade(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getUf())) : ?>
                                        <dt>UF:</dt>
                                        <dd><?php echo $loja->getUf(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getCep())) : ?>
                                        <dt>CEP:</dt>
                                        <dd><?php echo $loja->getCep(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getPosicaoStr())) : ?>
                                        <dt>Posição:</dt>
                                        <dd><?php echo $loja->getPosicaoStr(); ?></dd>
                                    <?php endif; ?>
                                </dl>
                                <hr />
                                <dl class="dl-horizontal">
                                    <?php if (LojaBLL::usaPagamentoOnline()) : ?>
                                    <!--dt>Gateway:</dt>
                                    <dd><?php echo $loja->getGatewayStr(); ?></dd-->
                                    <?php endif; ?>
                                    <dt>Opções:</dt>
                                    <dd>
                                        <i class="<?php echo ($loja->getControleEstoque() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                        Usa controle de estoque<br />
                                        <?php if (LojaBLL::usaPagamentoOnline()) : ?>
                                            <i class="<?php echo ($loja->getUsaGateway() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                            Usa gateways de pagamento<br />
                                        <?php endif; ?>
                                        <i class="<?php echo ($loja->getUsaRetirar() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                        Aceita retirada de produtos na loja<br />
                                        <?php if (LojaBLL::usaRetiradaMapeada()) : ?>
                                            <i class="<?php echo ($loja->getUsaRetiradaMapeada() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                            Aceita retirada de produtos mapeanda na loja<br />
                                        <?php endif; ?>
                                        <i class="<?php echo ($loja->getUsaEntregar() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                        A loja realiza entregas<br />
                                        <?php if (LojaBLL::usaPagamentoOnline()) : ?>
                                            <i class="<?php echo ($loja->getAceitaCreditoOnline() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                            Aceita pagamento por cartão de crédito online<br />
                                            <?php if (LojaBLL::usaDebitoOnline() == true) : ?>
                                                <i class="<?php echo ($loja->getAceitaDebitoOnline() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                                Aceita pagamento por cartão de débito online<br />
                                            <?php endif; ?>
                                            <i class="<?php echo ($loja->getAceitaBoleto() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                            Aceita pagamento por boleto bancário<br />
                                        <?php endif; ?>
                                        <i class="<?php echo ($loja->getAceitaDinheiro() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                        Aceita pagamento em dinheiro<br />
                                        <i class="<?php echo ($loja->getAceitaCartaoOffline() == true) ? "fa fa-check-square" : "fa fa-square" ?>"></i>
                                        Aceita Vale/Ticket/Cartão na Entrega<br />
                                    </dd>
                                    <?php if ($loja->getControleEstoque() == true) : ?>
                                        <dt>Estoque Mínimo:</dt>
                                        <dd><?php echo number_format($loja->getEstoqueMinimo(), 0, ",", "."); ?></dd>
                                    <?php endif; ?>
                                    <?php if ($loja->getValorMinimo() > 0) : ?>
                                        <dt>Valor Mínimo:</dt>
                                        <dd><?php echo $loja->getValorMinimoStr(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getDescricao())) : ?>
                                        <dt>Descrição:</dt>
                                        <dd><?php echo $loja->getDescricao(); ?></dd>
                                    <?php endif; ?>
                                    <?php if (!isNullOrEmpty($loja->getMensagemRetirada())) : ?>
                                        <dt>Mensagem a quem vai retirar:</dt>
                                        <dd><?php echo $loja->getMensagemRetirada(); ?></dd>
                                    <?php endif; ?>
                                </dl>
                            </div>
                        </div>
                        <?php if (count($loja->listarUsuario()) > 0) : ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <table class="table table-striped table-hover table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Situação</th>
                                        <th class="text-right"><i class="fa fa-cog"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($loja->listarUsuario() as $usuarioLoja) : ?>
                                        <?php $usuario = $usuarioLoja->getUsuario(); ?>
                                        <?php $url = $app->getBaseUrl() . "/usuario/" . $usuario->getSlug() . "/perfil"; ?>
                                        <?php $urlExcluir = $app->getBaseUrl() . "/loja/" . $loja->getSlug() . "/usuario/excluir/" . $usuario->getId(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo $url; ?>">
                                                    <?php if (UsuarioBLL::usaFoto() == true) : ?>
                                                        <img src="<?php echo $usuario->getThumbnailUrl(22, 22); ?>" class="img-circle" style="width: 22px; height: 22px;" />
                                                    <?php else : ?>
                                                        <i class="fa fa-user-circle"></i>
                                                    <?php endif; ?>
                                                    <?php echo $usuario->getNome(); ?>
                                                </a>
                                            </td>
                                            <td><a href="<?php echo $url; ?>"><?php echo $usuario->getEmail(); ?></a></td>
                                            <td><a href="<?php echo $url; ?>"><?php echo $usuario->getTelefoneStr(); ?></a></td>
                                            <td>
                                                <a href="<?php echo $url; ?>" class="<?php echo $usuario->getSituacaoClasse(); ?>">
                                                    <?php echo $usuario->getSituacaoStr(); ?>
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <a class="confirm" href="<?php echo $urlExcluir; ?>">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3" style="padding-top: 40px;">
                        <a href="<?php echo $app->getBaseUrl() . "/loja/" . $loja->getSlug() . "/alterar"; ?>"><i class="fa fa-fw fa-pencil"></i> Alterar</a><br />
                        <?php if ($usuario->temPermissao(LojaInfo::GERENCIAR_LOJA)) : ?>
                        <a class="confirm" href="<?php echo $app->getBaseUrl() . "/loja/" . $loja->getSlug() . "/excluir"; ?>"><i class="fa fa-fw fa-trash"></i> Excluir</a><br />
                        <?php endif; ?>
                        <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/fretes"; ?>"><i class="fa fa-fw fa-truck"></i> Valores de Frete</a><br />
                        <hr />
                        <a href="#usuarioModal" data-toggle="modal" data-target="#usuarioModal"><i class="fa fa-fw fa-plus"></i> Adicionar Usuário</a><br />
                        <?php /*if ($usuario->temPermissao(UsuarioInfo::ADMIN)) : ?>
                        <a href="#lojaModal" data-toggle="modal" data-target="#lojaModal"><i class="fa fa-fw fa-copy"></i> Copiar Produtos</a>
                        <?php endif;*/ ?>
                        <hr />
                        <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos"; ?>"><i class="fa fa-fw fa-shopping-cart"></i> Produtos</a><br />
                        <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/categorias"; ?>"><i class="fa fa-fw fa-reorder"></i> Categorias</a><br />
                        <!--a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades"; ?>"><i class="fa fa-fw fa-balance-scale"></i> Unidades</a><br /-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
