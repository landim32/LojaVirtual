<?php

namespace Emagine\Social\BLL;

use Emagine\Social\DALFactory\MensagemDALFactory;
use Exception;
use Emagine\Social\DAL\MensagemDAL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Social\Model\ContatoInfo;
use Emagine\Social\Model\MensagemInfo;

class MensagemBLL {

    /**
     * @return bool
     */
    public static function getExibirAviso() {
        if (defined("MENSAGEM_EXIBE_AVISO")) {
            return MENSAGEM_EXIBE_AVISO;
        }
        return false;
    }

    /**
     * @return bool
     */
    public static function getMensagemUsaRoute() {
        if (defined("MENSAGEM_USA_ROUTE")) {
            return (USUARIO_SOCIAL_USA_ROUTE == true);
        }
        return true;
    }

    /**
     * @throws Exception
     * @param int|null $id_usuario
     * @param bool|null $lido
     * @return MensagemInfo[]
     */
    public function listar($id_usuario = null, $lido = null) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $dal = MensagemDALFactory::create();
        return $dal->listar($id_usuario, $lido);
    }

    /**
     * Lista as mensagens que eu recebi
     * @throws Exception
     * @param int|null $id_usuario
     * @param bool|null $lido
     * @return MensagemInfo[]
     */
    public function listarMeu($id_usuario = null, $lido = null) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $dal = MensagemDALFactory::create();
        return $dal->listarMeu($id_usuario, $lido);
    }

    /**
     * Lista as mensagens enviadas por mim
     * @throws Exception
     * @param int $id_usuario
     * @param bool|null $lido
     * @return MensagemInfo[]
     */
    public function listarSaida($id_usuario = null, $lido = null) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $dal = MensagemDALFactory::create();
        return $dal->listarSaida($id_usuario, $lido);
    }

    /**
     * @throws Exception
     * @param MensagemInfo[] $mensagens
     * @param int $id_usuario
     * @return ContatoInfo[]
     */
    public function listarContato($mensagens, $id_usuario = null) {

        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }

        $contatos = array();
        foreach ($mensagens as $mensagem) {
            if ($mensagem->getIdUsuario() != $id_usuario) {
                if (array_key_exists($mensagem->getIdAutor(), $contatos)) {
                    $contato = $contatos[$mensagem->getIdAutor()];
                    $contato->adicionarMensagem($mensagem);
                }
                else {
                    $contato = new ContatoInfo();
                    $contato->setIdUsuario($mensagem->getIdUsuario());
                    $contato->setNome($mensagem->getUsuario()->getNome());
                    $contato->setFoto($mensagem->getUsuario()->getFoto());
                    $contato->adicionarMensagem($mensagem);
                    $contatos[$contato->getIdUsuario()] = $contato;
                }
            }
            elseif ($mensagem->getIdAutor() != $id_usuario) {
                if (array_key_exists($mensagem->getIdAutor(), $contatos)) {
                    $contato = $contatos[$mensagem->getIdAutor()];
                    $contato->adicionarMensagem($mensagem);
                }
                else {
                    $contato = new ContatoInfo();
                    $contato->setIdUsuario($mensagem->getIdAutor());
                    //$contato->setNome($mensagem->getAutor()->getNome());
                    //$contato->setFoto($mensagem->getAutor()->getFoto());
                    $contato->adicionarMensagem($mensagem);
                    $contatos[$contato->getIdUsuario()] = $contato;
                }
            }
        }

        return $contatos;
    }

    /**
     * @throws Exception
     * @param int $id_mensagem
     * @return MensagemInfo
     */
    public function pegar($id_mensagem) {
        $dal = MensagemDALFactory::create();
        return $dal->pegar($id_mensagem);
    }

    /**
     * @param MensagemInfo|null $mensagem
     * @return MensagemInfo
     */
    public function pegarDoPost($mensagem = null) {
        if (is_null($mensagem))
            $mensagem = new MensagemInfo();
        if (array_key_exists('id_usuario', $_POST))
            $mensagem->setIdUsuario($_POST['id_usuario']);
        if (array_key_exists('id_remetente', $_POST))
            $mensagem->setIdAutor($_POST['id_remetente']);
        //if (array_key_exists('id_imovel', $_POST))
        //    $mensagem->id_imovel = $_POST['id_imovel'];
        if (array_key_exists('mensagem', $_POST))
            $mensagem->setMensagem( $_POST['mensagem'] );
        return $mensagem;
    }

    /**
     * @param MensagemInfo $mensagem
     * @throws Exception
     */
    public function validar(&$mensagem) {
        if (is_null($mensagem)) {
            throw new Exception(_('Message not informed!'));
        }
        if ($mensagem->getIdUsuario() <= 0) {
            throw new Exception(_('User is empty!'));
        }
        if (isNullOrEmpty($mensagem->getMensagem()))
            throw new Exception(_('Message is empty!'));
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param int|null $id_autor
     */
    public function marcarLido($id_usuario, $id_autor = null) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $dal = MensagemDALFactory::create();
        $dal->marcarLido($id_usuario, $id_autor);
    }

    /**
     * @throws Exception
     * @param MensagemInfo $mensagem
     * @return int
     */
    public function inserir($mensagem = null) {
        if (is_null($mensagem))
            $mensagem = $this->pegarDoPost();
        $this->validar($mensagem);
        $dal = MensagemDALFactory::create();
        return $dal->inserir($mensagem);
    }

    /**
     * @param MensagemInfo $mensagem
     */
    public function enviarEmail($mensagem) {
        /*
        $autor = $mensagem->getAutor();
        $usuario = $mensagem->getUsuario();
        $GLOBALS['mensagem'] = $mensagem;

        ob_start();
        include(dirname(__DIR__)."/tmpl/email-mensagem.inc.php");
        $conteudo = ob_get_contents();
        ob_end_clean();

        $assunto = '[' . RevendedorBLL::getPrincipalNome() . '] '.$autor->getNome().' enviou uma nova mensagem para você';
        $regraEmail = new EmailBLL();
        $regraEmail->sendmail_mailjet($usuario->getEmail1(), $assunto, $conteudo, $usuario->getNome());
        $regraEmail->sendmail(EMAIL_ADMIN, $assunto, $conteudo, $usuario->getNome());
        */
    }

    /**
     * @throws Exception
     * @param int $id_mensagem
     */
    public function excluir($id_mensagem) {
        $dal = MensagemDALFactory::create();
        $dal->excluir($id_mensagem);
    }

    /**
     * @throws Exception
     * @deprecated Use MensagemBLL->enviarMensagem($id_usuario, $texto)
     * @param int $id_usuario
     * @param string $texto
     * @return int
     */
    public function enviar_mensagem($id_usuario, $texto) {
        return $this->enviarMensagem($id_usuario, $texto);
    }

    /**
     * @throws Exception
     * @param int $id_usuario
     * @param string $texto
     * @return int
     */
    public function enviarMensagem($id_usuario, $texto) {
        $mensagem = new MensagemInfo();
        $mensagem->setIdUsuario( $id_usuario );
        $mensagem->setIdAutor(UsuarioBLL::pegarIdUsuarioAtual());
        $mensagem->setMensagem( $texto );

        $id_mensagem = $this->inserir($mensagem);
        $mensagem->setId( $id_mensagem );
        $this->enviarEmail($mensagem);

        return $mensagem->getId();
    }

    /**
     * @deprecated Use MensagemBLL->botaoMensagem($usuario, $class, $tooltip)
     * @param UsuarioInfo $usuario
     * @param string $class
     * @param bool $tooltip
     */
    public function botao_mensagem($usuario, $class = '', $tooltip = false) {
        $this->botaoMensagem($usuario, $class, $tooltip);
    }

    /**
     * @param UsuarioInfo $usuario
     * @param string $class
     * @param bool $tooltip
     */
    public function botaoMensagem($usuario, $class = '', $tooltip = false) {
        echo '<a href="#" class="mensagem';
        if (!isNullOrEmpty($class))
            echo ' '.$class;
        if ($tooltip) {
            echo ' bs-tooltip" ';
            echo 'data-toggle="tooltip" data-placement="top" ';
            echo 'data-original-title="Enviar mensagem"';
        }
        else
            echo '" ';
        echo 'data-usuario="' . $usuario->getId() . '" ';
        echo 'data-nome="' . $usuario->getNome() . '" ';
        echo 'data-foto="' . $usuario->getFoto() . '" ><i class="fa fa-comment"></i>';
        if (!$tooltip) {
            echo ' Enviar mensagem';
        }
        echo '</a>';
    }
}