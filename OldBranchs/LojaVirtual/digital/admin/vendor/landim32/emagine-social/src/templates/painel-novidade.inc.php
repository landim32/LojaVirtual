<?php

use Imobsync\BLL\ContaBLL;
use Imobsync\BLL\UsuarioBLL;
use Imobsync\BLL\FinanceiroBLL;
use Imobsync\BLL\UsuarioSocialBLL;

$regraConta = new ContaBLL();
$regraUsuario = new UsuarioBLL();
$regraSocial = new UsuarioSocialBLL();
$regraFinanceiro = new FinanceiroBLL();

global $usuario, $faturas;

$id_usuario = intval($_GET['usuario']);
if ($id_usuario == 0)
    $id_usuario = ID_USUARIO;

$usuario = $regraUsuario->pegar($id_usuario);
$empresa = $regraConta->pegar($usuario->getIdConta());

$colegas = $regraUsuario->listarPorConta($empresa->id_conta);
$amigos = $regraSocial->listarAmigo($id_usuario);

$apartir_de = null;

$novidades = $regraUsuario->listarNovidade($usuario, 30);
$faturas = $regraFinanceiro->listarMeu(1);

//var_dump($novidades);
//exit();
?>
<style type="text/css">
.valor-promocional .plano {
    text-align: center;
    padding-right: 3px;
    color: #adadad;
    display: block;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    padding: 3px 0px;
}
.valor-promocional {
    padding-right: 3px;
    font-size: 28px;
    font-weight: 800;
    overflow: hidden;
}
.valor-promocional sup {
    font-size: 14px;
    font-weight: normal;
    top: -0.8em;
}
.valor-promocional .desconto {
    /*margin-top: -3px;*/
    padding-right: 3px;
    font-size: 11px;
    font-weight: 600;
    overflow: hidden;
}
</style>
<div class="page-header">
    <div class="page-title">
        <h3><?php echo _('Timeline'); ?></h3>
        <span><?php echo _('A summary of all that is happening.'); ?></span>
    </div>
    <ul class="page-stats">
        <li>
            <div class="summary statbox">
                <div class="visual red">
                    <i class="icon icon-home"></i>
                </div>
                <span><?php echo _('Properties for Partnership'); ?></span>
                <h3><?php echo IMOVEL_PARCERIA_QUANTIDADE; ?></h3>
            </div>
        </li>
        <?php if (PRETENSAO_QUANTIDADE > 0) : ?>
        <li>
            <div class="summary statbox">
                <div class="visual green">
                    <i class="icon icon-user"></i>
                </div>
                <span><?php echo _('Opportunities'); ?></span>
                <h3><?php echo PRETENSAO_QUANTIDADE; ?></h3>
            </div>
        </li>
        <?php endif; ?>
    </ul>
</div>
<div class="row row-bg">
    <div class="col-md-4">
        <div class="widget box box-shadow">
            <div class="widget-content">
                <?php if (!isNullOrEmpty($usuario->getFoto())) : ?>
                <img src="<?php echo $regraUsuario->getFotoUrl($usuario->getFoto(), 80, 80) ?>" class="img-circle pull-left" style="margin-right: 5px" />
                <?php else : ?>
                <img src="/img/80x80/anonimo.png" class="img-circle pull-left" style="margin-right: 5px" />
                <?php endif; ?>
                <h4><b><?php echo $usuario->getNome(); ?></b></h4>
                <p><small>
                    <?php if (!isNullOrEmpty($usuario->getCreci())) : ?>
                    <?php echo _('Creci'); ?>: <b><?php echo $usuario->getCreci(); ?></b><br />
                    <?php endif; ?>
                    <?php echo _('Work at'); ?> <b><?php echo $empresa->nome; ?></b>
                </small></p>

               <?php if (count($amigos) > 0) : ?>
               <hr />
               <h5><?php echo _('Friends'); ?> (<?php echo count($amigos); ?>)</h5>
               <?php foreach ($amigos as $amigo) : ?>
               <a href="social-perfil?id=<?php echo $amigo->id_usuario; ?>" class="bs-tooltip" data-placement="right" data-original-title="<?php echo $amigo->nome; ?>">
               <?php if (!isNullOrEmpty($amigo->foto)) : ?>
               <img src="<?php echo $regraUsuario->getFotoUrl($amigo->foto, 50, 50, $amigo->id_conta) ?>" class="img-circle" style="margin-bottom: 5px" />
               <?php else : ?>
               <img src="/img/50x50/anonimo.png" class="img-circle" style="margin-bottom: 5px" />
               <?php endif; ?>
               </a>
               <?php endforeach; ?>
               <?php endif; ?>

               <?php if ((count($colegas) - 1) > 0) : ?>
               <hr />
               <h5><?php echo _('Colleagues'); ?> (<?php echo count($colegas) - 1; ?>)</h5>
               <?php foreach ($colegas as $colega) : ?>
               <?php if ($colega->id_usuario != $usuario->getId()) : ?>
               <a href="social-perfil?id=<?php echo $colega->id_usuario; ?>" class="bs-tooltip" data-placement="right" data-original-title="<?php echo $colega->nome; ?>">
               <?php if (!isNullOrEmpty($colega->foto)) : ?>
               <img src="<?php echo $regraUsuario->getFotoUrl($colega->foto, 50, 50, $colega->id_conta) ?>" class="img-circle" style="margin-bottom: 5px" />
               <?php else : ?>
               <img src="/img/50x50/anonimo.png" class="img-circle" style="margin-bottom: 5px" />
               <?php endif; ?>
               </a>
               <?php endif; ?>
               <?php endforeach; ?>
               <?php endif; ?>
               <div class="clearfix"></div>
            </div>
        </div>
        <?php if (E_GRATUITO) : ?>
        <?php require('painel-plano.inc.php'); ?>
        <?php if (count($faturas) > 0) : ?>
        <?php require('painel-fatura.inc.php'); ?>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="col-lg-6 col-md-8">
        <?php foreach ($novidades as $novidade) : ?>
        <div class="widget box box-shadow">
            <div class="widget-content">
                <div>
                    <a target="_blank" href="<?php echo $novidade->url; ?>">
                    <?php if (!isNullOrEmpty($novidade->getUsuarioFoto())) : ?>
                    <img src="<?php echo $novidade->getUsuarioFoto(); ?>" class="img-circle pull-left" style="margin: 0px 5px" />
                    <?php else : ?>
                    <img src="/img/50x50/anonimo.png" class="img-circle pull-left" style="margin: 0px 5px" />
                    <?php endif; ?>
                    </a>
                    <a target="_blank" href="<?php echo $novidade->url; ?>"><?php echo $novidade->getNome(); ?></a><br />
                    <small class="text-muted"><a target="_blank" href="<?php echo $novidade->url; ?>"><?php echo humanizeDateDiff(time(), $novidade->getUltimaAlteracao()); ?></a></small>
                    <?php if ($novidade->getPrivado() == true) : ?>
                    <span class="label label-danger"><?php echo _('Private'); ?></span>
                    <?php endif; ?>
                    <hr />
                    <?php if (isset($novidade->imovel)) : ?>
                    <h4 class="text-center"><a href="<?php echo $novidade->url; ?>"><b><?php echo $novidade->getTitulo(); ?></b></a></h4>
                    <?php 
                        $comissao = 0;
                        $imovel = $novidade->imovel;
                        if ($imovel->getPreco() > 0) {
                            if ($imovel->getVendaComissaoValor() > 0 && $imovel->getVendaComissaoPorcentagem() > 0) {
                                $comissao = $imovel->getVendaComissaoValor();
                                $comissao += $imovel->getPreco() * $imovel->getVendaComissaoPorcentagem() / 100;
                            }
                            elseif ($imovel->getVendaComissaoValor() > 0) {
                                $comissao = $imovel->getVendaComissaoValor();
                            }
                            elseif ($imovel->getVendaComissaoPorcentagem() > 0) {
                                $comissao = $imovel->getPreco() * $imovel->getVendaComissaoPorcentagem() / 100;
                            }
                        }
                     ?>
                    <div class="row">
                        <div class="col-md-4">
                            <a target="_blank" href="<?php echo $novidade->url; ?>">
                            <?php if ($imovel->getIdFoto() > 0) : ?>
                            <img src="<?php echo $imovel->getThumbnailUrl(320, 240) ?>" alt="<?php echo $imovel->getTitulo(); ?>" class="img-responsive" />
                            <?php else : ?>
                            <img src="/timthumb.php?src=admin/images/imovel-sem-foto.jpg&amp;w=320&amp;h=240&amp;zc=1" alt="<?php echo $imovel->getTitulo(); ?>" class="img-responsive" />
                            <?php endif; ?>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="<?php echo ($comissao > 0) ? 'col-md-6' : 'col-md-12'; ?>">
                                    <h5><b><?php echo $imovel->getTipoImovel(); ?></b></h5>
                                    <?php if (!isNullOrEmpty($imovel->getBairro())) : ?>
                                    <small><i><?php echo _('neighborhood'); ?>:</i></small> <?php echo $imovel->getBairro(); ?><br />
                                    <?php endif; ?>
                                    <small><i><?php echo _('city'); ?>:</i></small> <?php echo $imovel->getCidade(); ?> / <?php echo $imovel->getUf(); ?><br />
                                    <?php if ($imovel->getPreco() > 0) : ?>
                                    <small><i><?php echo _('price'); ?>:</i></small> <b><?php echo number_format($imovel->getPreco(), 2, ',', '.'); ?></b>
                                    <?php endif; ?>
                                </div>
                                <?php if ($comissao > 0) : ?>
                                <div class="col-md-6 text-center">
                                    <div class="widget box box-shadow">
                                        <div class="widget-header">
                                            <h5><i class="icon icon-dollar"></i> <?php echo _('Commission'); ?></h5>
                                        </div>
                                        <div class="widget-content">
                                            <div class="valor-promocional"><sup>R$ </sup><?php echo str_replace(',', '<sup> ,', number_format($comissao, 2, ',', '.')).'</sup>'; ?></div>
                                        </div>
                                    </div>            
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php else : ?>
                    <?php if (!isNullOrEmpty($novidade->foto)) : ?>
                    <a target="_blank" class="pull-left" href="<?php echo $novidade->url; ?>"><img src="<?php echo $novidade->foto; ?>" class="img-thumbnail" style="margin-right: 5px" /></a>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($novidade->getTitulo())) : ?>
                    <h5><a target="_blank" href="<?php echo $novidade->url; ?>"><b><?php echo $novidade->getTitulo();  ?></b></a></h5>
                    <?php endif; ?>
                    <?php if (!isNullOrEmpty($novidade->getSubTitulo())) : ?>
                    <a target="_blank" style="font-style: italic;" href="<?php echo $novidade->url; ?>"><?php echo $novidade->getSubTitulo();  ?></a>
                    <?php endif; ?>
                    <p><a target="_blank" href="<?php echo $novidade->url; ?>"><?php echo $novidade->getDescricao(); ?></a></p>
                    <?php endif; ?>
                    
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>