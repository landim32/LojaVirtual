<?php
namespace Emagine\Pagamento\Iugu\BLL;

use Emagine\Pagamento\Exceptions\PagamentoException;
use stdClass;
use Exception;
use Iugu;
use Iugu_Charge;
use Iugu_SearchResult;
use Emagine\Log\BLL\LogBLL;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\BLL\UsuarioBLL;

class IuguBLL
{
    /**
     * @return string
     */
    public function getApiUrl() {
        return "https://api.iugu.com/v1";
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getIuguIdAccount() {
        if (!defined("IUGU_ACCOUNT_ID")) {
            throw new Exception("IUGU_ACCOUNT_ID não foi definido.");
        }
        return IUGU_ACCOUNT_ID;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getIuguToken() {
        if (!defined("IUGU_TOKEN")) {
            throw new Exception("IUGU_TOKEN não foi definido.");
        }
        return IUGU_TOKEN;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getIuguEmail() {
        if (!defined("IUGU_EMAIL")) {
            throw new Exception("IUGU_EMAIL não foi definido.");
        }
        return IUGU_EMAIL;
        //return "diegocarvalho.advogado@gmail.com";
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getAutenticacao() {
        return $this->getIuguToken() . ": ";
    }

    /**
     * @throws Exception
     * @param string $pathUrl
     * @param string[] $postData
     * @return stdClass
     */
    public function post($pathUrl, $postData) {

        $regraLog = new LogBLL();
        $usuario = UsuarioBLL::pegarUsuarioAtual();

        $url = $this->getApiUrl() . $pathUrl;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /*
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));
        */

        $output = curl_exec($ch);
        curl_close($ch);

        $logStr = "";
        $logStr .= "\$ch = curl_init(\"" . $url . "\");\n";
        $logStr .= "curl_setopt(\$ch, CURLOPT_CUSTOMREQUEST, \"POST\");\n";
        $logStr .= "curl_setopt(\$ch, CURLOPT_POSTFIELDS, " . print_r($postData, true) . ");\n";
        $logStr .= "curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, true);\n";
        $logStr .= "\$output = curl_exec(\$ch);\n";
        $logStr .= "curl_close(\$ch);\n";
        $logStr .= "<hr />\n";
        $logStr .= $output;

        $log = new LogInfo();
        $log->setCodTipo(LogInfo::INFORMACAO);
        if (!is_null($usuario)) {
            $log->setNome($usuario->getNome());
        }
        $log->setTitulo("Novo cartão de crédito via Iugu");
        $log->setDescricao($logStr);
        $regraLog->inserir($log);

        $retorno = json_decode($output);
        $this->processarErro($retorno);
        return $retorno;
    }

    /**
     * @throws Exception
     * @param array $postData
     * @return Iugu_Charge
     */
    public function charge($postData) {

        $regraLog = new LogBLL();
        $usuario = UsuarioBLL::pegarUsuarioAtual();

        Iugu::setApiKey($this->getIuguToken());
        /** @var Iugu_Charge $retorno */
        $retorno = Iugu_Charge::create($postData);

        $logStr = "";
        $logStr .= "Iugu::setApiKey(\"" . $this->getIuguToken() . "\");\n";
        $logStr .= "\$retorno = Iugu_Charge::create(";
        $logStr .= print_r($postData, true);
        $logStr .= ");\n";
        $logStr .= "<hr />\n";
        $logStr .= print_r($retorno, true);

        $log = new LogInfo();
        $log->setCodTipo(LogInfo::INFORMACAO);
        if (!is_null($usuario)) {
            $log->setNome($usuario->getNome());
        }
        $log->setTitulo("Pagamento via Iugu");
        $log->setDescricao($logStr);
        $regraLog->inserir($log);

        //$this->processarErro($retorno);
        return $retorno;
    }

    /**
     * @param stdClass|Iugu_SearchResult $retorno
     * @throws Exception
     */
    private function processarErro($retorno) {
        if (isset($retorno->errors)) {
            $mensagem = null;
            if (isset($retorno->errors)) {
                if (is_object($retorno->errors)) {
                    $mensagem = array_values((Array)$retorno->errors)[0];
                    if (is_object($mensagem)) {
                        $mensagem = array_values((Array)$mensagem)[0];
                    }
                    elseif (is_array($mensagem)) {
                        $mensagem = array_values($mensagem)[0];
                    }
                }
                elseif (is_array($retorno->errors)) {
                    $mensagem = array_values($retorno->errors)[0];
                }
                elseif (is_string($retorno->errors)) {
                    $mensagem = $retorno->errors;
                }
            }
            if (!is_null($mensagem) && is_string($mensagem)) {
                if (startsWith($mensagem, "do cartão")) {
                    $mensagem = "Número " . $mensagem;
                }
                throw new PagamentoException($mensagem);
            }
        }
    }
}