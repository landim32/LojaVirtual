<?php

namespace Emagine\Social;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Social\BLL\UsuarioSocialBLL;
use Emagine\Social\BLL\MensagemBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Social\Factory\MensagemFactory;
use Emagine\Social\Model\RelacionamentoInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var UsuarioInfo $usuario
 * @var EmagineApp $app
 */

/*
require('common.inc.php');

use Imobsync\BLL\ContaBLL;
use Imobsync\BLL\UsuarioBLL;
use Imobsync\BLL\MensagemBLL;
use Imobsync\BLL\UsuarioSocialBLL;
use Imobsync\Controls\Page;

define('E_PERFIL', true);

global $usuario;

$id_usuario = intval($_GET['id']);
if ($id_usuario == 0)
    $id_usuario = ID_USUARIO;

$regraConta = new ContaBLL();
$regraUsuario = new UsuarioBLL();
$regraMensagem = new MensagemBLL();
$regraSocial = new UsuarioSocialBLL();

try {

    $usuario = $regraUsuario->pegar($id_usuario);
    $empresa = $regraConta->pegar($usuario->getIdConta());
    $relacionamento = $regraSocial->pegarRelacionamento($usuario->getId());

    if (array_key_exists('ac', $_POST)) {
        if ($_POST['ac'] == 'novo-post') {
            $regraSocial->escrever($_POST['mensagem']);
        }
        elseif ($_POST['ac'] == 'comentar') {
            $id_post = intval($_POST['id_post']);
            $id_imovel = intval($_POST['id_imovel']);
            if ($id_post > 0)
                $regraSocial->comentarPost($id_post, $_POST['mensagem']);
            elseif ($id_imovel > 0)
                $regraSocial->comentarImovel($id_imovel, $_POST['mensagem']);
        }
        header('location: social-perfil');
        exit();
    }

    if (array_key_exists('ac', $_GET)) {
        switch ($_GET['ac']) {
            case 'solicitar-amizade':
                $regraSocial->solicitarAmizade($id_usuario);
                break;
            case 'aceitar-amizade':
                $regraSocial->aceitarAmizade($id_usuario);
                break;
            case 'cancelar-amizade':
                $regraSocial->desfazerAmizade($id_usuario);
                break;
            case 'tornar-parceria':
                $regraSocial->aceitarAmizade($id_usuario, 2);
                break;
            case 'tornar-amizade':
                $regraSocial->aceitarAmizade($id_usuario, 1);
                break;
            case 'curtir':
                $id_post = intval($_GET['post']);
                $id_imovel = intval($_GET['imovel']);
                if ($id_post > 0)
                    $regraSocial->curtirPost($id_post);
                elseif ($id_imovel > 0)
                    $regraSocial->curtirImovel($id_imovel);
                break;
        }
        header('location: social-perfil?id='.$id_usuario);
        exit();
    }
}
catch (Exception $e) {
    $msgErro = $e->getMessage();
}
*/

$regraSocial = new UsuarioSocialBLL();
$regraMensagem = MensagemFactory::create();

$id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
/** @var RelacionamentoInfo $relacionamento */
$relacionamento = $regraSocial->pegarRelacionamento($usuario->getId());

?>
<div class="container" style="margin-top: 70px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <?php /*$GLOBALS['novidades'] = $regraUsuario->listarPerfil($usuario, 30); ?>
            <?php if (count($GLOBALS['novidades']) > 0) : ?>
            <?php require('timeline.php'); ?>
            <?php else :*/ ?>
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="icon icon-info-circle"></i>
                Nenhuma novidade disponível!
            </div>
            <?php //endif; ?>
        </div>
        <div class="col-md-3">
            <?php if ($usuario->getId() != $id_usuario) : ?>
                <div class="btn-group-vertical btn-block" role="group">
                    <?php $regraMensagem->botaoMensagem($usuario, 'btn btn-default'); ?>
                    <?php if (is_null($relacionamento)) : ?>
                        <a class="btn btn-default" href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=solicitar-amizade">
                            <i class="fa fa-fw fa-plus"></i> Requisitar Amizade
                        </a>
                    <?php elseif ($relacionamento->getTipo() == 0) : ?>
                        <?php if ($relacionamento->getIdOrigem() == $id_usuario) : ?>
                            <a class="btn btn-default" href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=cancelar-amizade">
                                <i class="icon icon-remove"></i> Aguardando resposta(Cancelar)
                            </a>
                        <?php else : ?>
                            <a class="btn btn-default" href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=aceitar-amizade">
                                <i class="icon icon-ok"></i> Aceitar amizade
                            </a>
                            <a class="btn btn-default" href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=cancelar-amizade">
                                <i class="icon icon-remove"></i> Rejeitar amizade
                            </a>
                        <?php endif; ?>
                    <?php elseif ($relacionamento->getTipo() == RelacionamentoInfo::AMIGO) : ?>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon icon-user"></i> Amigo
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=tornar-parceria" title=""><i class="icon icon-plus"></i> Parceiro</a></li>
                                <li><a href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=cancelar-amizade" title=""><i class="icon icon-plus"></i> Desfazer amizade</a></li>
                            </ul>
                        </div>
                    <?php elseif ($relacionamento->getTipo() == RelacionamentoInfo::PARCEIRO) : ?>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon icon-user"></i> Parceiro
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=tornar-amizade" title=""><i class="icon icon-plus"></i> Amigo</a></li>
                                <li><a href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=cancelar-amizade" title=""><i class="icon icon-plus"></i> Cancelar Parceria</a></li>';
                            </ul>
                        </div>
                    <?php elseif ($relacionamento->getTipo() == RelacionamentoInfo::BLOQUEADO) : ?>
                        <a class="btn btn-default" href="social-perfil?id=<?php echo $usuario->getId(); ?>&ac=desbloquear">
                            <i class="icon icon-user-times"></i> Bloqueado
                        </a>
                    <?php endif; ?>
                </div>
                <hr />
                <a class="btn btn-block btn-danger" href="#ticketModal" data-toggle="modal"><i class="fa fa-warning"></i> Denunciar</a>
            <?php endif; ?>
        </div>
    </div>
</div>