<?php

namespace Emagine\Social\BLL;

use Exception;
use Emagine\Social\DAL\UsuarioSocialDAL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Social\Model\RelacionamentoInfo;

class UsuarioSocialBLL extends UsuarioBLL {

    const POST = 1;
    const POST_COMENTARIO = 2;
    const POST_COMPARTILHAR = 3;
    const POST_CURTIR = 4;

    /**
     * @return bool
     */
    public static function getSocialUsaRoute() {
        if (defined("USUARIO_SOCIAL_USA_ROUTE")) {
            return (USUARIO_SOCIAL_USA_ROUTE == true);
        }
        return true;
    }

    /**
     * Envia o convite por email
     * @param string $email
     * @param string $nome
     * @throws Exception
     */
    private function enviarConvite($email, $nome) {

        if (isNullOrEmpty($email))
            throw new Exception(_('Email cant be empty.'));
        if (isNullOrEmpty($nome))
            $nome = $email;

        global $conta, $usuario, $destinatario;

        $regraUsuario = new UsuarioBLL();
        $conta = ContaBLL::pegarAtual();
        //$usuario = $regraUsuario->pegarAtual();
        $usuario = UsuarioBLL::getUsuarioAtual();


        $destinatario = new \stdClass();
        $destinatario->nome = $nome;
        $destinatario->email = $email;

        ob_start();
        include(dirname(__DIR__)."/tmpl/tmpl-email-convite.inc.php");
        $content = ob_get_contents();
        ob_end_clean();

        $assunto = $usuario->getNome().' está convidando você para ser seu parceiro no ' . RevendedorBLL::getPrincipalNome();
        $regraEmail = new EmailBLL();
        $regraEmail->sendmail_mailjet($email, $assunto, $content, $usuario->getNome());
        $regraEmail->sendmail(EMAIL_ADMIN, $assunto, $content, $usuario->getNome());

        $this->gravarConvite($email, $nome);
    }

    /**
     * Pega o usuário caso tenha um convite enviado
     * @param string $email
     * @return UsuarioInfo|null
     */
    public function pegarConvite($email) {
        $dal = new UsuarioSocialDAL();
        return $dal->pegarConvite($email);
    }

    public function convidar($nome, $email) {
        $usuarios = $this->buscarPorEmail($email);
        if (count($usuarios) > 0) {
            foreach ($usuarios as $usuario)
                $this->solicitarAmizade($usuario->getId());
        }
        else
            $this->enviarConvite($email, $nome);
    }

    /**
     * Pega a quantidade de amigo de um determinado usuário
     * @param int $id_usuario
     * @return int
     */
    public function quantidadeAmigo($id_usuario) {
        $dal = new UsuarioSocialDAL();
        return $dal->quantidadeAmigo($id_usuario);
    }

    /*
    public function buscarPorEmail($email) {
        $query = $this->query()."
            WHERE '".do_escape($email)."' IN (
                usuario.email1, usuario.email2,
                usuario.email3, usuario.email4
            )
            AND usuario.ativo = 1
            ORDER BY nome
        ";
        return get_result($query);
    }
    */

    /**
     * Busca por usuários
     * @param string $palavra
     * @param int $limite
     * @return UsuarioInfo[]
     */
    public function buscarAmigo($palavra, $limite = 15) {
        $dal = new UsuarioSocialDAL();
        return $dal->buscarAmigo($palavra, $limite);
    }

    /**
     * Lista os amigos
     * @param int|null $id_usuario
     * @return UsuarioInfo[]
     */
    public function listarAmigo($id_usuario = null) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $dal = new UsuarioSocialDAL();
        return $dal->listarAmigo($id_usuario);
    }

    /**
     * Listar os usuários com solicitações
     * @param int|null $id_usuario
     * @return UsuarioInfo[]
     */
    public function listarSolicitacao($id_usuario = null) {
        if (is_null($id_usuario)) {
            $id_usuario = UsuarioBLL::pegarIdUsuarioAtual();
        }
        $dal = new UsuarioSocialDAL();
        return $dal->listarSolicitacao($id_usuario);
    }

    /**
     * Solicitar amizade
     * @param int $id_usuario
     */
    public function solicitarAmizade($id_usuario) {
        $usuario = $this->pegar($id_usuario);
        if (!isNullOrEmpty($usuario)) {
            $this->inserirAmizade(UsuarioBLL::pegarIdUsuarioAtual(), $id_usuario, 0, true);
            $this->enviarConvite($usuario->getEmail(), $usuario->getNome());
        }
    }

    /**
     * @param int $id_usuario
     * @return RelacionamentoInfo
     */
    public function pegarRelacionamento($id_usuario) {
        $dal = new UsuarioSocialDAL();
        return $dal->pegarRelacionamento(UsuarioBLL::pegarIdUsuarioAtual(), $id_usuario);
    }

    /**
     * Inserir relacionamento
     * @param int $id_origem
     * @param int $id_destino
     * @param int $tipo
     * @param bool $seguir
     */
    public function inserirAmizade($id_origem, $id_destino, $tipo, $seguir) {
        $relacionemento = new RelacionamentoInfo();
        $relacionemento->setIdOrigem($id_origem);
        $relacionemento->setIdDestino($id_destino);
        $relacionemento->setTipo($tipo);
        $relacionemento->setSeguir($seguir);

        $dal = new UsuarioSocialDAL();
        $dal->inserirAmizade($relacionemento);
    }

    /**
     * Inserir relacionamento
     * @param RelacionamentoInfo $relacionamento
     */
    public function inserirRelacionamento($relacionamento) {
        $dal = new UsuarioSocialDAL();
        $dal->inserirRelacionamento($relacionamento);
    }

    /**
     * @param int $id_origem
     * @param int $id_destino
     * @param int $tipo
     * @param bool $seguir
     */
    public function alterarAmizade($id_origem, $id_destino, $tipo, $seguir) {
        $relacionamento = new RelacionamentoInfo();
        $relacionamento->setIdOrigem($id_origem);
        $relacionamento->setIdDestino($id_destino);
        $relacionamento->setTipo($tipo);
        $relacionamento->setSeguir($seguir);

        $dal = new UsuarioSocialDAL();
        $dal->alterarRelacionamento($relacionamento);
    }

    /**
     * Aceitar uma amizade solicitada
     * @param int $id_usuario
     * @param int $tipo
     */
    public function aceitarAmizade($id_usuario, $tipo = 1) {
        $dal = new UsuarioSocialDAL();
        $relacionamento = $dal->pegarRelacionamento(UsuarioBLL::pegarIdUsuarioAtual(), $id_usuario);
        if (!is_null($relacionamento)) {
            $relacionamento->setSeguir(true);
            $dal->alterarRelacionamento($relacionamento);
        }
        else {
            $this->inserirAmizade(UsuarioBLL::pegarIdUsuarioAtual(), $id_usuario, $tipo, true);
        }
    }

    /**
     * Exclui relacionamento com um determinado usuário
     * @param int $id_usuario
     */
    public function desfazerAmizade($id_usuario) {
        $dal = new UsuarioSocialDAL();
        $dal->excluirRelacionamento(UsuarioBLL::pegarIdUsuarioAtual(), $id_usuario);
    }
}
