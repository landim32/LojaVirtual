<?php

namespace Emagine\Base\BLL;

use Exception;

class MailJetBLL extends EmailBLL {
    /**
     * @throws Exception
     * @return string
     */
    protected function getHost() {
        if (!defined('MAILJET_HOST')) {
            throw new Exception("MAILJET_HOST não foi definido.");
        }
        return MAILJET_HOST;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getUsername() {
        if (!defined('MAILJET_USERNAME')) {
            throw new Exception("MAILJET_USERNAME não foi definido.");
        }
        return MAILJET_USERNAME;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getPassword() {
        if (!defined('MAILJET_PASSWORD')) {
            throw new Exception("MAILJET_PASSWORD não foi definido.");
        }
        return MAILJET_PASSWORD;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getEmailRemetente() {
        if (!defined('EMAIL_REMETENTE')) {
            throw new Exception("EMAIL_REMETENTE não foi definido.");
        }
        return EMAIL_REMETENTE;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getNomeRemetente() {
        if (!defined('NOME_REMETENTE')) {
            throw new Exception("NOME_REMETENTE não foi definido.");
        }
        return NOME_REMETENTE;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getEmail() {
        if (!defined('MAILJET_EMAIL')) {
            throw new Exception("MAILJET_EMAIL não foi definido.");
        }
        return MAILJET_EMAIL;
    }
}