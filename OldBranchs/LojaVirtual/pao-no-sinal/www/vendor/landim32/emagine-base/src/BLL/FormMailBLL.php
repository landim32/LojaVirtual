<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 01/03/2018
 * Time: 18:11
 */

namespace Emagine\Base\BLL;

use Exception;
use Emagine\Base\Model\FormMailInfo;

class FormMailBLL extends MailJetBLL
{
    /**
     * @throws Exception
     * @param string[] $postData
     * @param FormMailInfo|null $formmail
     * @return FormMailInfo
     */
    public function pegarDoPost($postData, $formmail = null) {
        if (is_null($formmail)) {
            $formmail = new FormMailInfo();
        }
        $params = array(
            "nome", "name", "email", "from", "assunto", "subject",
            "mensagem", "conteudo", "body", "descricao"
        );
        if (array_key_exists("nome", $postData)) {
            $formmail->setNome($postData["nome"]);
        }
        elseif (array_key_exists("name", $postData)) {
            $formmail->setNome($postData["name"]);
        }
        else {
            $formmail->setNome($this->getNomeRemetente());
        }
        if (array_key_exists("email", $postData)) {
            $formmail->setEmail($postData["email"]);
        }
        elseif (array_key_exists("from", $postData)) {
            $formmail->setEmail($postData["from"]);
        }
        else {
            $formmail->setEmail($this->getEmailRemetente());
        }
        if (array_key_exists("assunto", $postData)) {
            $formmail->setAssunto($postData["assunto"]);
        }
        elseif (array_key_exists("subject", $postData)) {
            $formmail->setAssunto($postData["subject"]);
        }
        else {
            $formmail->setAssunto("Sem assunto");
        }

        if (array_key_exists("mensagem", $postData)) {
            $formmail->setMensagem($postData["mensagem"]);
        }
        elseif (array_key_exists("conteudo", $postData)) {
            $formmail->setMensagem($postData["conteudo"]);
        }
        elseif (array_key_exists("body", $postData)) {
            $formmail->setMensagem($postData["body"]);
        }
        elseif (array_key_exists("descricao", $postData)) {
            $formmail->setMensagem($postData["descricao"]);
        }

        $campos = array();
        foreach ($postData as $nome => $conteudo) {
            if (!in_array($nome, $params)) {
                $campos[$nome] = $conteudo;
            }
        }
        $formmail->setCampos($campos);

        return $formmail;
    }

    /**
     * @param FormMailInfo $formmail
     * @return string
     */
    protected function gerarHtml(FormMailInfo $formmail) {
        $html = "";
        $html .= "<table width=\"100%\"><tr><td align=\"center\"><table width=\"600\">\n";
        $html .= "<tr><th align='right'>Nome:</th><td>" . $formmail->getNome() . "</td></tr>\n";
        $html .= "<tr><th align='right'>Email:</th><td>" . $formmail->getEmail() . "</td></tr>\n";
        $html .= "<tr><th align='right'>Mensagem:</th><td>" . $formmail->getMensagem() . "</td></tr>\n";
        foreach ($formmail->getCampos() as $titulo => $conteudo) {
            $html .= "<tr><th align='right'>" . $titulo . ":</th><td>" . $conteudo . "</td></tr>\n";
        }
        $html .= "<tr><th align='right'>Endereço IP:</th><td>" . $_SERVER['REMOTE_ADDR'] . "</td></tr>\n";
        $html .= "<tr><th align='right'>Hora do Envio:</th><td>" . date("d/m/Y H:i:s") . "</td></tr>\n";
        $html .= "</table></td></tr></table>\n";
        return $html;
    }

    /**
     * @param FormMailInfo $formmail
     * @throws Exception
     */
    public function enviar(FormMailInfo $formmail) {
        $this->send(
            $this->getEmail(),
            $formmail->getAssunto(),
            $this->gerarHtml($formmail),
            $this->getNomeRemetente(),
            $this->getEmailRemetente()
        );
    }
}