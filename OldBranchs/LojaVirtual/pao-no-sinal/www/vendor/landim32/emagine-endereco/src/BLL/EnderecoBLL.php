<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 19/04/2018
 * Time: 08:01
 */

namespace Emagine\Endereco\BLL;

use Exception;
use Emagine\Base\BLL\CookieBLL;
use Emagine\Endereco\Model\EnderecoInfo;

class EnderecoBLL
{
    const ENDERECO_COOKIE = "ENDERECO_COOKIE";

    /**
     * @return bool
     */
    public static function usaBaseCep() {
        if (defined("USA_BASE_CEP")) {
            return (USA_BASE_CEP == true);
        }
        return true;
    }

    /**
     * @param string[] $postData
     * @param EnderecoInfo $endereco
     * @return EnderecoInfo
     */
    public function pegarDoPost($postData, $endereco = null) {
        if (is_null($endereco)) {
            $endereco = new EnderecoInfo();
        }
        if (array_key_exists("cep", $postData)) {
            $endereco->setCep($postData["cep"]);
        }
        if (array_key_exists("logradouro", $postData)) {
            $endereco->setLogradouro($postData["logradouro"]);
        }
        if (array_key_exists("complemento", $postData)) {
            $endereco->setComplemento($postData["complemento"]);
        }
        if (array_key_exists("numero", $postData)) {
            $endereco->setNumero($postData["numero"]);
        }
        if (array_key_exists("bairro", $postData)) {
            $endereco->setBairro($postData["bairro"]);
        }
        if (array_key_exists("cidade", $postData)) {
            $endereco->setCidade($postData["cidade"]);
        }
        if (array_key_exists("uf", $postData)) {
            $endereco->setUf($postData["uf"]);
        }
        if (array_key_exists("latitude", $postData)) {
            $endereco->setLatitude($postData["latitude"]);
        }
        if (array_key_exists("longitude", $postData)) {
            $endereco->setLongitude($postData["longitude"]);
        }
        return $endereco;
    }

    /**
     * @return EnderecoInfo|null
     */
    public function pegarAtual() {
        $cookie = new CookieBLL();
        $texto = $cookie->pegar(EnderecoBLL::ENDERECO_COOKIE);
        if (isNullOrEmpty($texto)) {
            return null;
        }
        $json = json_decode($texto);
        if (is_null($json)) {
            return null;
        }
        return EnderecoInfo::fromJson($json);
    }

    /**
     * @param EnderecoInfo $endereco
     * @throws Exception
     */
    public function gravarAtual(EnderecoInfo $endereco) {
        $cookie = new CookieBLL();
        $texto = json_encode($endereco);
        $cookie->gravar(EnderecoBLL::ENDERECO_COOKIE, $texto);
    }

    /**
     * Remover Cookie de endereço
     */
    public function limparAtual() {
        $cookie = new CookieBLL();
        $cookie->remover(EnderecoBLL::ENDERECO_COOKIE);
    }
}