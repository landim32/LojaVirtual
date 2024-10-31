<?php

namespace Emagine\Base\BLL;

use Exception;
use PHPMailer;

class EmailBLL {

    /**
     * @throws Exception
     * @return string
     */
    protected function getHost() {
        if (!defined('MAIL_HOST')) {
            throw new Exception("MAIL_HOST não foi definido.");
        }
        return MAIL_HOST;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getUsername() {
        if (!defined('MAIL_USERNAME')) {
            throw new Exception("MAIL_USERNAME não foi definido.");
        }
        return MAIL_USERNAME;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getPassword() {
        if (!defined('MAIL_PASSWORD')) {
            throw new Exception("MAIL_PASSWORD não foi definido.");
        }
        return MAIL_PASSWORD;
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
        if (!defined('MAIL_EMAIL')) {
            throw new Exception("MAIL_EMAIL não foi definido.");
        }
        return MAIL_EMAIL;
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $html
     * @param string $fromName
     * @param string $from
     * @param string $bcc
     * @throws Exception
     */
    public function sendmail($to, $subject, $html, $fromName = null, $from = null, $bcc = null) {
        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = $this->getHost();
        $mail->SMTPAuth = true;
        $mail->Username = $this->getUsername();
        $mail->Password = $this->getPassword();
        $mail->SMTPSecure = 'tls';

        if (isNullOrEmpty($from)) {
            $from = $this->getEmailRemetente();
        }

        if (isNullOrEmpty($fromName)) {
            $fromName = $this->getNomeRemetente();
        }

        $mail->From = $this->getEmail();
        $mail->FromName = $fromName;
        $mail->AddReplyTo($from, $fromName);

        if (strpos($to, ",") === false) {
            $mail->AddAddress(trim($to));
        }
        else {
            $emails = explode(',', $to);
            foreach ($emails as $email) {
                $mail->AddAddress(trim($email));
            }
        }
        if (!is_null($bcc)) {
            $mail->AddBCC($bcc);
        }
        else {
            $mail->AddBCC('rodrigo@emagine.com.br');
        }

        $mail->WordWrap = 50;
        $mail->IsHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = strip_tags($html);

        if(!$mail->Send()) {
            throw new Exception($mail->ErrorInfo);
        }
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $html
     * @param string $fromName
     * @param string $from
     * @param string $bcc
     */
    public function send($to, $subject, $html, $fromName = null, $from = null, $bcc = null) {
        $this->sendmail($to, $subject, $html, $fromName, $from, $bcc);
    }
}