<?php

use Emagine\Social\Model\ContatoInfo;
use Emagine\Social\Model\MensagemInfo;

/*
require('common.inc.php');

use Imobsync\BLL\UsuarioBLL;
use Imobsync\BLL\MensagemBLL;
use Imobsync\Controls\Page;
use Imobsync\Model\ContatoInfo;

$contato = new ContatoInfo();
$regraUsuario = new UsuarioBLL();
$regraMensagem = new MensagemBLL();

try {

    if (count($_POST) > 0 && array_key_exists('ac', $_POST) && $_POST['ac'] == 'nova-mensagem') {
        $id_usuario = intval($_POST['id_usuario']);
        $mensagem = trim($_POST['mensagem']);
        if ($id_usuario <= 0)
            $msgerro = _('Select the user!');
        elseif (isNullOrEmpty($mensagem))
            $msgerro = _('Enter the message!');
        else {
            try {
                $regraMensagem->enviarMensagem($id_usuario, $mensagem);
                $msgsucesso = _('Message sent successfully!');
            }
            catch (Exception $e) {
                $msgerro = $e->getMessage();
            }
        }
    }

    if (array_key_exists('excluir', $_GET)) {
        $id_mensagem = intval($_GET['excluir']);
        if ($id_mensagem > 0)
            $regraMensagem->excluir($id_mensagem);
    }
}
catch (Exception $e) {
    $msgerro = $e->getMessage();
}

Page::header();
Page::contentStart();
breadcrumb_menu(array(
        array(
            'current' => false,
            'icon' => 'icon icon-bar-chart',
            'url' => 'index',
            'nome' => _('Home')
        ),
        array(
            'current' => false,
            'icon' => 'icon icon-globe',
            'url' => 'social-busca',
            'nome' => _('Social Network')
        ),
        array(
            'current' => true,
            'icon' => 'icon icon-envelope',
            'url' => 'mensagem',
            'nome' => _("Inbox")
        ),
    )
);
//$mensagens = $regraMensagem->listar(ID_USUARIO);
$mensagens = $regraMensagem->listar(ID_USUARIO);
$id_usuario = null;
if (array_key_exists('usuario', $_GET))
    $id_usuario = intval($_GET['usuario']);
$contatos = $regraMensagem->listarContato($mensagens);

if (!($id_usuario > 0) && count($contatos) > 0) {
    list($id_usuario, $contato) = each($contatos);
    $id_usuario = $contato->getIdUsuario();
}

if ($id_usuario > 0 && array_key_exists($id_usuario, $contatos)) {
    $regraMensagem->marcarLido($id_usuario);
    $mensagens = $contato->listarMensagem($id_usuario);
}
if ($id_usuario > 0)
    $usuario = $regraUsuario->pegar($id_usuario);
else
    $usuario = null;
*/

//var_dump($contatos);

/** @var ContatoInfo[] $contatos */
$contatos = $contatos;
/** @var MensagemInfo[] $mensagens */
$mensagens = $mensagens;
?>
<?php if (count($contatos) > 0) : ?>
<div class="row">
    <div class="col-md-8">
        <!--pre><?php //var_dump($mensagens); ?></pre-->
        <?php if (isset($msgsucesso)) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $msgsucesso; ?>
        </div>
        <?php elseif (isset($msgerro)) : ?>
        <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fa fa-warning"></i> <strong><?php echo _('Error!')?></strong> <?php echo $msgerro; ?>
        </div>
        <?php endif; ?>
        <div class="list-group">
            <?php foreach ($mensagens as $mensagem) : ?>
            <div class="list-group-item">
                <h5 class="list-group-item-heading">
                    <span class="pull-right">
                        <small><?php echo humanizeDateDiff(time(), strtotime($mensagem->getDataInclusao())); ?></small>
                        <a href="#" data-mensagem="<?php echo $mensagem->getId(); ?>" class="mensagem-excluir"><i class="fa fa-trash-o"></i></a>
                    </span>
                    <?php if (!is_null($mensagem->getAutor())) : ?>
                    <img src="<?php echo $mensagem->getAutor()->getFoto(); ?>" class="img-circle" style="width: 40px; height: 40px;" />
                    <?php echo $mensagem->getAutor()->getNome(); ?>
                    <?php endif; ?>
                </h5>
                <p class="list-group-item-text" style="padding-left: 30px">
                    <?php echo $mensagem->getMensagem(); ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="text-right">
            <a href="#" class="mensagem btn btn-block btn-primary"
               data-usuario="<?php echo $usuario->getId(); ?>"
               data-nome="<?php echo $usuario->getNome(); ?>"
               data-foto="<?php echo $usuario->getFoto(); ?>"
            ><i class="fa fa-comment"></i> Enviar mensagem</a>
        </div>
        <div class="clearfix"></div>
        <hr />
        <div class="list-group">
            <?php foreach ($contatos as $contato) : ?>
                <a href="#social/mensagens?usuario=<?php echo $contato->getIdUsuario(); ?>" class="list-group-item<?php echo ($contato->getIdUsuario() == $id_usuario) ? ' active' : ''; ?>">
                    <h5 class="list-group-item-heading">
                        <img src="<?php echo $contato->getFoto(); ?>" class="img-circle" style="width: 40px; height: 40px;" />
                        <?php truncate_str($contato->getNome(), 25); ?>
                    </h5>
                    <p class="list-group-item-text" style="padding-left: 30px">
                        <?php if (count($contato->listarMensagem($id_usuario)) > 0) : ?>
                            <?php $mensagem = $contato->listarMensagem($id_usuario)[0]; ?>
                            <small><?php echo humanizeDateDiff(time(), strtotime($mensagem->getDataInclusao())); ?></small>
                        <?php endif; ?>
                    </p>
                </a>
                <?php //var_dump($contato); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php else : ?>
<div class="alert alert-warning" role="alert">
    <i class="fa fa-warning"></i> Você não tem nenhuma mensagem!
</div>
<?php endif; ?>