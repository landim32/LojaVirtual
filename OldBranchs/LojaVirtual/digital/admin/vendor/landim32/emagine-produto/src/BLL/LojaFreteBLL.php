<?php
namespace Emagine\Produto\BLL;

use Exception;
use Emagine\Produto\DAL\LojaFreteDAL;
use Emagine\Produto\Model\LojaFreteInfo;

class LojaFreteBLL
{

    /**
     * @return bool
     */
    public static function usaFreteRoute() {
        if (defined("USA_LOJA_FRETE_ROUTE")) {
            return (USA_LOJA_FRETE_ROUTE == true);
        }
        return true;
    }

    /**
     * @param $id_loja
     * @return LojaFreteInfo[]
     * @throws Exception
     */
    public function listar($id_loja) {
        $dal = new LojaFreteDAL();
        return $dal->listar($id_loja);
    }

    /**
     * @param string[] $postParam
     * @param LojaFreteInfo|null $frete
     * @return LojaFreteInfo
     */
    public function pegarDoPost($postParam, $frete = null) {
        if (is_null($frete)) {
            $frete = new LojaFreteInfo();
        }
        if (array_key_exists("id_frete", $postParam)) {
            $frete->setId($postParam["id_frete"]);
        }
        if (array_key_exists("id_loja", $postParam)) {
            $frete->setIdLoja($postParam["id_loja"]);
        }
        if (array_key_exists("uf", $postParam)) {
            $frete->setUf($postParam["uf"]);
        }
        if (array_key_exists("cidade", $postParam)) {
            $frete->setCidade($postParam["cidade"]);
        }
        if (array_key_exists("bairro", $postParam)) {
            $frete->setBairro($postParam["bairro"]);
        }
        if (array_key_exists("logradouro", $postParam)) {
            $frete->setLogradouro($postParam["logradouro"]);
        }
        if (array_key_exists("valor_frete", $postParam)) {
            $frete->setValorFrete(floatvalx($postParam["valor_frete"]));
        }
        $frete->setEntrega(intval($postParam["entrega"]) == 1);
        return $frete;
    }

    /**
     * @throws Exception
     * @param int $id_frete
     * @return LojaFreteInfo
     */
    public function pegar($id_frete) {
        $dal = new LojaFreteDAL();
        return $dal->pegar($id_frete);
    }

    /**
     * @param int $id_loja
     * @param string|null $uf
     * @param string|null $cidade
     * @param string|null $bairro
     * @param string|null $logradouro
     * @return LojaFreteInfo
     * @throws Exception
     */
    public function pegarPorEndereco($id_loja, $uf = null, $cidade = null, $bairro = null, $logradouro = null) {
        $dal = new LojaFreteDAL();
        if (!isNullOrEmpty($logradouro)) {
            $frete = $dal->pegarPorEndereco($id_loja, $uf, $cidade, $bairro, $logradouro);
            if (!is_null($frete)) {
                return $frete;
            }
        }
        if (!isNullOrEmpty($bairro)) {
            $frete = $dal->pegarPorEndereco($id_loja, $uf, $cidade, $bairro);
            if (!is_null($frete)) {
                return $frete;
            }
        }
        if (!isNullOrEmpty($cidade)) {
            $frete = $dal->pegarPorEndereco($id_loja, $uf, $cidade);
            if (!is_null($frete)) {
                return $frete;
            }
        }
        if (!isNullOrEmpty($uf)) {
            $frete = $dal->pegarPorEndereco($id_loja, $uf);
            if (!is_null($frete)) {
                return $frete;
            }
        }
        $frete = $dal->pegarPorEndereco($id_loja);
        if (!is_null($frete)) {
            return $frete;
        }
        $frete = new LojaFreteInfo();
        $frete->setValorFrete(0);
        $frete->setEntrega(true);
        return $frete;
    }

    /**
     * @throws Exception
     * @param LojaFreteInfo $frete
     * @return int
     */
    public function inserir($frete) {
        $dal = new LojaFreteDAL();
        return $dal->inserir($frete);
    }

    /**
     * @throws Exception
     * @param LojaFreteInfo $frete
     */
    public function alterar($frete) {
        $dal = new LojaFreteDAL();
        $dal->alterar($frete);
    }

    /**
     * @throws Exception
     * @param int $id_frete
     */
    public function excluir($id_frete) {
        $dal = new LojaFreteDAL();
        $dal->excluir($id_frete);
    }
}