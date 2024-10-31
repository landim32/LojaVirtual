<?php
namespace Emagine\Pagamento\MundiPagg\BLL;

use stdClass;
use Exception;
use Emagine\Pagamento\Exceptions\DesconhecidoException;
use Emagine\Pagamento\Exceptions\PagamentoException;

class MundiPaggBLL
{
    /**
     * @return string
     */
    public function getApiUrl() {
        return "https://api.mundipagg.com/core/v1";
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getChaveSecreta() {
        if (!defined("MUNDIPAGG_CHAVE_PRIVADA")) {
            throw new PagamentoException("A chave privada não está definida.");
        }
        return MUNDIPAGG_CHAVE_PRIVADA;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getChavePublica() {
        if (!defined("MUNDIPAGG_CHAVE_PUBLICA")) {
            throw new PagamentoException("A chave pública não está definida.");
        }
        return MUNDIPAGG_CHAVE_PUBLICA;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getBancoCodigo() {
        if (!defined("MUNDIPAGG_BOLETO_CODIGO")) {
            throw new PagamentoException("A código do banco não está definida.");
        }
        return MUNDIPAGG_BOLETO_CODIGO;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getAutenticacao() {
        return "Basic " . base64_encode($this->getChavePublica() . ":" . $this->getChaveSecreta());
    }

    /**
     * @param stdClass $retorno
     * @throws Exception
     */
    private function processarErro(stdClass $retorno) {
        if (!($retorno instanceof stdClass)) {
            throw new PagamentoException("O gateway retornou uma resposta vazia!");
        }
        if (isset($retorno->message)) {
            $mensagem = null;
            if (isset($retorno->errors)) {
                if (is_object($retorno->errors)) {
                    $mensagem = array_values((Array)$retorno->errors)[0];
                    if (is_array($mensagem)) {
                        $mensagem = array_values($mensagem)[0];
                    }
                }
            }
            if (isNullOrEmpty($mensagem)) {
                $mensagem = $retorno->message;
            }
            throw new PagamentoException($mensagem);
        }
        else {
            throw new DesconhecidoException("Erro desconhecido!");
        }
    }

    /**
     * @throws Exception
     * @param string $pathUrl
     * @param stdClass $data
     * @return stdClass
     */
    public function post($pathUrl, stdClass $data) {
        $conteudo = json_encode($data);

        $ch = curl_init( $this->getApiUrl() . $pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $conteudo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Authorization: " . $this->getAutenticacao(),
                "Content-Length: " . strlen($conteudo))
        );

        $retornoStr = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //fwrite(STDERR, $retornoStr);

        $retorno = json_decode($retornoStr);

        if ($httpCode == 200) {
            return $retorno;
        }
        else {
            $this->processarErro($retorno);
        }
        return null;
    }

    /**
     * @param string $pathUrl
     * @return stdClass
     * @throws Exception
     */
    public function get($pathUrl) {
        $ch = curl_init( $this->getApiUrl() . $pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: " . $this->getAutenticacao()
        ));

        $retornoStr = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $retorno = json_decode($retornoStr);

        if ($httpCode == 200) {
            return $retorno;
        }
        else {
            $this->processarErro($retorno);
        }
        return null;
    }

    /**
     * @param string $pathUrl
     * @return stdClass
     * @throws Exception
     */
    public function delete($pathUrl) {
        $ch = curl_init( $this->getApiUrl() . $pathUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: " . $this->getAutenticacao()
        ));

        $retornoStr = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $retorno = json_decode($retornoStr);

        if ($httpCode == 200) {
            return $retorno;
        }
        else {
            $this->processarErro($retorno);
        }
        return null;
    }
}