<?php

namespace Emagine\Social;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Imobsync\Conta\Model\ContaInfo;
use Imobsync\Imovel\Model\ImovelInfo;
use Imobsync\Imovel\Model\ImovelExpiraInfo;
use Emagine\Social\Model\NovidadeInfo;

/**
 * @var int $quantidadeExpirado
 * @var int $quantidadeExpira
 * @var EmagineApp $app
 * @var ContaInfo $conta
 * @var ImovelInfo[] $imovelExpirado
 * @var ImovelInfo[] $imovelExpira
 * @var NovidadeInfo[] $novidades
 */

$id_usuario = UsuarioBLL::pegarIdUsuarioAtual();

?>
<style type="text/css">
    .timeline {
        position: relative;
        padding: 21px 0px 10px;
        margin-top: 4px;
        margin-bottom: 30px;
    }

    .timeline .line {
        position: absolute;
        width: 4px;
        display: block;
        background: currentColor;
        top: 0px;
        bottom: 0px;
        margin-left: 30px;
    }

    .timeline .separator {
        border-top: 1px solid currentColor;
        padding: 5px;
        padding-left: 40px;
        font-style: italic;
        font-size: .9em;
        margin-left: 30px;
    }

    .timeline .line::before { top: -4px; }
    .timeline .line::after { bottom: -4px; }
    .timeline .line::before,
    .timeline .line::after {
        content: '';
        position: absolute;
        left: -4px;
        width: 12px;
        height: 12px;
        display: block;
        border-radius: 50%;
        background: currentColor;
    }

    .timeline .panel {
        position: relative;
        margin: 10px 0px 21px 70px;
        clear: both;
    }

    .timeline .panel::before {
        position: absolute;
        display: block;
        top: 8px;
        left: -24px;
        content: '';
        width: 0px;
        height: 0px;
        border: inherit;
        border-width: 12px;
        border-top-color: transparent;
        border-bottom-color: transparent;
        border-left-color: transparent;
    }

    .timeline .panel .panel-heading.icon * { font-size: 20px; vertical-align: middle; line-height: 40px; }
    .timeline .panel .panel-heading.icon {
        position: absolute;
        left: -59px;
        display: block;
        width: 40px;
        height: 40px;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        float: left;
    }

    .timeline .panel-outline {
        border-color: transparent;
        background: transparent;
        box-shadow: none;
    }

    .timeline .panel-outline .panel-body {
        padding: 10px 0px;
    }

    .timeline .panel-outline .panel-heading:not(.icon),
    .timeline .panel-outline .panel-footer {
        display: none;
    }
</style>
<?php if ($quantidadeExpirado > 1) : ?>
<div id="alert-expirado" class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <i class="icon icon-warning pull-left" style="font-size: 30px; margin-top: 5px"></i> Você tem <strong><?php echo $quantidadeExpirado; ?></strong> imóveis expirados. <a href="#">Clique aqui</a> para visualiza-los.<br />
    <a class="btn btn-xs btn-success btn-reativar-todos" data-imovel="<?php //echo $imovel->getId(); ?>" href="#"><i class="icon icon-check"></i> Reativar todos</a>
    ou 
    <a class="btn btn-xs btn-danger btn-inativar-todos" data-imovel="<?php //echo $imovel->getId(); ?>" href="#"><i class="icon icon-remove"></i> inativar todos definitivamente</a>
</div>
<?php endif; ?>
<?php if ($quantidadeExpira > 1) : ?>
<div id="alert-expira" class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <i class="icon icon-warning pull-left" style="font-size: 30px; margin-top: 5px"></i> Você tem <strong><?php echo $quantidadeExpira; ?></strong> imóveis que estão para expirar.<br />
    Para atualizar esses imóveis por mais 60 dias <a class="btn btn-xs btn-success btn-atualizar-todos" href="#">Clique aqui</a>.
</div>
<?php endif; ?>


<div class="timeline">
    <!-- Line component -->
    <div class="line text-muted"></div>

    <?php /*
    <!-- Separator -->
    <div class="separator text-muted">
        <time>26. 3. 2015</time>
    </div>
    <!-- /Separator -->

    <!-- Panel -->
    <article class="panel panel-danger panel-outline">

        <!-- Icon -->
        <div class="panel-heading icon">
            <i class="glyphicon glyphicon-heart"></i>
        </div>
        <!-- /Icon -->

        <!-- Body -->
        <div class="panel-body">
            <strong>Someone</strong> favourited your photo.
        </div>
        <!-- /Body -->

    </article>
    <!-- /Panel -->

    <!-- Panel -->
    <article class="panel panel-default panel-outline">

        <!-- Icon -->
        <div class="panel-heading icon">
            <i class="glyphicon glyphicon-picture"></i>
        </div>
        <!-- /Icon -->

        <!-- Body -->
        <div class="panel-body">
            <img class="img-responsive img-rounded" src="//placehold.it/350x150" />
        </div>
        <!-- /Body -->

    </article>
    <!-- /Panel -->
     * 
     */ ?>
    
    <?php //var_dump($imovelExpirado); exit(); ?>

    <?php
    /** @var ImovelExpiraInfo $imovel */
    /*
    foreach ($imovelExpirado as $imovel) : ?>
    <article id="<?php echo "imovel_" . $imovel->getIdImovel(); ?>" class="panel panel-danger">
        <div class="panel-heading icon">
            <i class="icon icon-clock-o"></i>
        </div>
        <div class="panel-body">
            <?php echo $imovel->getDescricaoHtml(); ?>
        </div>
        <div class="panel-footer text-right">
            <a class="btn btn-xs btn-success btn-reativar" data-imovel="<?php echo $imovel->getIdImovel(); ?>" href="#"><i class="icon icon-check"></i> Reativar</a>
            ou 
            <a class="btn btn-xs btn-danger btn-inativar" data-imovel="<?php echo $imovel->getIdImovel(); ?>" href="#"><i class="icon icon-remove"></i> inativar definitivamente</a>
        </div>
    </article>
    <?php endforeach;*/ ?>
    
    
    <?php //var_dump($imovelExpira); ?>
    <?php foreach ($imovelExpira as $imovel) : ?>
    <?php if ($imovel->dias <= 0) continue; ?>
    <article id="<?php echo "imovel_" . $imovel->getId(); ?>" class="panel panel-danger">
        <div class="panel-heading icon">
            <i class="icon icon-clock-o"></i>
        </div>
        <div class="panel-body">
            <?php echo $imovel->getDescricaoHtml(); ?>
        </div>
        <div class="panel-footer text-right">
            <a class="btn btn-xs btn-success btn-atualizar" data-imovel="<?php echo $imovel->getId(); ?>" href="#"><i class="icon icon-check"></i> Atualizar por mais 60 dias</a>
            ou 
            <a class="btn btn-xs btn-danger btn-inativar" data-imovel="<?php echo $imovel->getId(); ?>" href="#"><i class="icon icon-remove"></i> inativar o anúncio</a>
        </div>
    </article>
    <?php endforeach; ?>

    <?php if (count($novidades) > 0) : ?>
    <?php $ultimaData = null; ?>
    <?php $primeiroItem = true; ?>
    <?php foreach ($novidades as $novidade) : ?>
    <?php //var_dump($novidade); ?>
    <?php //$dataAtual = date('d. M. Y', $novidade->getUltimaAlteracao()); ?>
    <?php $dataAtual  = humanizeDateDiff(time(), $novidade->getUltimaAlteracao()); ?>
    <?php $dataAtual .= ' ('.strftime('%a, %d de %B de %Y', $novidade->getUltimaAlteracao()).')'; ?>
    <?php if ($dataAtual != $ultimaData) : ?>
    <div class="separator text-muted">
        <time><?php echo $dataAtual; ?></time>
    </div>
    <?php $ultimaData = $dataAtual; ?>
    <?php endif; ?>

    <?php if ($novidade->getTipo() == 'exclusividade') : ?>
    <article class="panel panel-danger panel-outline">
        <div class="panel-heading icon">
            <i class="icon icon-remove"></i>
        </div>
        <div class="panel-body">
            <a href="<?php echo "imovel?id=".$novidade->getIdImovel(); ?>"><?php echo $novidade->getSubTitulo(); ?></a>
        </div>
    </article>
    <?php else : ?>
    <article <?php echo ($primeiroItem == true) ? 'id="primeiro-registro-div"' : ''; $primeiroItem = false; ?> class="<?php echo (is_null($novidade->getImovel())) ? 'panel panel-info' : 'panel panel-success'; ?>">
        <div class="panel-heading icon">
            <i class="<?php echo (is_null($novidade->getImovel())) ? 'icon icon-user' : 'icon icon-home'; ?>"></i>
        </div>
        <div class="panel-heading">
            <span class="label <?php echo (is_null($novidade->getImovel())) ? 'label-info' : 'label-success'; ?> pull-right"><?php echo _('Partnership'); ?></span>
            <h2 class="panel-title">
                <?php if (!isNullOrEmpty($novidade->getUrl())) : ?>
                <a href="<?php echo $novidade->getUrl(); ?>"><b><?php echo $novidade->getTitulo(); ?></b></a>
                <?php else : ?>
                <b><?php echo $novidade->getTitulo(); ?></b>
                <?php endif; ?>
            </h2>
        </div>
        <div class="panel-body">
            <div>
                <?php if ($novidade->getTipo() == 'imovel' && !is_null($novidade->getImovel())) : ?>
                <?php 
                    $comissao = 0;
                    /** @var ImovelInfo $imovel */
                    $imovel = $novidade->getImovel();
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
                        <a target="_blank" href="<?php echo $novidade->getUrl(); ?>">
                        <?php if ($imovel->getIdFoto() > 0) : ?>
                        <img src="<?php echo $imovel->getThumbnailUrl(320, 240) ?>" alt="<?php echo $imovel->getTitulo(); ?>" class="img-responsive" />
                        <?php else : ?>
                        <img src="/timthumb.php?src=admin/images/imovel-sem-foto.jpg&amp;w=320&amp;h=240&amp;zc=1" alt="<?php echo $imovel->getTitulo(); ?>" class="img-responsive" />
                        <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-md-8">
                        <?php //var_dump($imovel); ?>
                        <div class="row">
                            <div class="<?php echo ($comissao > 0) ? 'col-md-6' : 'col-md-12'; ?>">
                                <h5><b><?php echo $imovel->getTipoImovel(); ?></b></h5>
                                <?php if (!isNullOrEmpty($imovel->getBairro())) : ?>
                                <small><i></i></small> <?php echo $imovel->getBairro(); ?><br />
                                <?php endif; ?>
                                <small><i></i></small> <?php echo $imovel->getCidade(); ?> / <?php echo $imovel->getUf(); ?><br />
                                <?php if ($imovel->getPreco() > 0) : ?>
                                <small><i><?php echo _('R$'); ?></i></small> <b><?php echo number_format($imovel->getPreco(), 2, ',', '.'); ?></b>
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

                <?php elseif ($novidade->getTipo() == 'exclusividade') : ?>
                
                <?php else : ?>
                <?php if (!isNullOrEmpty($novidade->getFoto())) : ?>
                <?php if (!isNullOrEmpty($novidade->getUrl())) : ?>
                <a target="_blank" class="pull-left" href="<?php echo $novidade->getUrl(); ?>"><img src="<?php echo $novidade->getFoto(); ?>" class="img-thumbnail" style="margin-right: 5px" /></a>
                <?php else : ?>
                <img src="<?php echo $novidade->getFoto(); ?>" class="img-thumbnail" style="margin-right: 5px" />
                <?php endif; ?>
                <?php endif; ?>
                <?php /*if (!isNullOrEmpty($novidade->getTitulo())) : ?>
                <h5><a target="_blank" href="<?php echo $novidade->url; ?>"><b><?php echo $novidade->getTitulo();  ?></b></a></h5>
                <?php endif;*/ ?>
                <?php if (!isNullOrEmpty($novidade->getSubTitulo())) : ?>
                <?php if (!isNullOrEmpty($novidade->getUrl())) : ?>
                <a target="_blank" style="font-style: italic;" href="<?php echo $novidade->getUrl(); ?>"><?php echo $novidade->getSubTitulo();  ?></a>
                <?php else : ?>
                <span style="font-style: italic;"><?php echo $novidade->getSubTitulo();  ?></span>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (!isNullOrEmpty($novidade->getUrl())) : ?>
                <p><a target="_blank" href="<?php echo $novidade->getUrl(); ?>"><?php echo $novidade->getDescricao(); ?></a></p>
                <?php else : ?>
                <p><?php echo $novidade->getDescricao(); ?></p>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!defined('E_PERFIL') || (defined('E_PERFIL') && E_PERFIL == false)) : ?>
        <div class="panel-footer">
            <?php //var_dump($novidade->getUsuarioFoto()); ?>
            <div class="row">
                <div class="col-md-7">
                    <a href="social-perfil?id=<?php echo $novidade->getIdUsuario(); ?>">
                    <img src="<?php echo $novidade->getUsuarioThumbnailUrl(24, 24); ?>" class="img-circle pull-left" style="margin: 0px 5px" />
                    </a>
                    <a href="social-perfil?id=<?php echo $novidade->getIdUsuario(); ?>"><?php echo $novidade->getNome(); ?></a><br />
                    <small class="text-muted"><a href="social-perfil?id=<?php echo $novidade->getIdUsuario(); ?>"><?php echo humanizeDateDiff(time(), $novidade->getUltimaAlteracao()); ?></a></small>
                </div>
                <div class="col-md-5 text-right">
                    <?php if ($novidade->getIdUsuario() != $id_usuario) : ?>
                    <a href="#" style="margin: 4px 0px" class="mensagem btn btn-sm btn-default" 
                       data-usuario="<?php echo $novidade->getIdUsuario(); ?>"
                       data-nome="<?php echo $novidade->getNome(); ?>"
                       data-foto="<?php echo $novidade->getUsuarioThumbnailUrl(40, 40); ?>"
                    ><i class="icon icon-comment"></i> Enviar Mensagem</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- /Footer -->

    </article>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
