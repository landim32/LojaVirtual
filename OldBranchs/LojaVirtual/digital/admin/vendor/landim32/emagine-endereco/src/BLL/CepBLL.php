<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 23/03/2018
 * Time: 17:19
 */

namespace Emagine\Endereco\BLL;

use Exception;
use Emagine\Endereco\DAL\CepDAL;
use Emagine\Endereco\Model\UfInfo;
use Emagine\Endereco\Model\CidadeInfo;
use Emagine\Endereco\Model\BairroInfo;
use Emagine\Endereco\Model\EnderecoInfo;

class CepBLL
{
    /**
     * @param $cep
     * @return EnderecoInfo
     * @throws Exception
     */
    public function pegarPorCep($cep) {
        $dal = new CepDAL();
        return $dal->pegarPorCep($cep);
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return string
     * @throws Exception
     */
    public function pegarCepMaisProximo($latitude, $longitude) {
        $dal = new CepDAL();
        return $dal->pegarCepMaisProximo($latitude, $longitude);
    }

    /**
     * @throws Exception
     * @param string $uf
     * @return CidadeInfo
     */
    public function pegarCidadeAleatoria($uf) {
        $dal = new CepDAL();
        return $dal->pegarCidadeAleatoria($uf);
    }

    /**
     * @return UfInfo[]
     * @throws Exception
     */
    public function listarUf() {
        $dal = new CepDAL();
        return $dal->listarUf();
    }

    /**
     * @param string $palavraChave
     * @param string $uf
     * @return CidadeInfo[]
     * @throws Exception
     */
    public function buscarCidadePorUf($palavraChave, $uf) {
        $dal = new CepDAL();
        return $dal->buscarCidadePorUf($palavraChave, $uf);
    }

    /**
     * @param string $palavraChave
     * @param int $id_cidade
     * @return BairroInfo[]
     * @throws Exception
     */
    public function buscarBairroPorIdCidade($palavraChave, $id_cidade) {
        $dal = new CepDAL();
        return $dal->buscarBairroPorIdCidade($palavraChave, $id_cidade);
    }

    /**
     * @param string $palavraChave
     * @param string $uf
     * @param string $cidade
     * @return BairroInfo[]
     * @throws Exception
     */
    public function buscarBairro($palavraChave, $uf, $cidade) {
        $dal = new CepDAL();
        return $dal->buscarBairro($palavraChave, $uf, $cidade);
    }

    /**
     * @param string $palavraChave
     * @param int $id_bairro
     * @return EnderecoInfo[]
     * @throws Exception
     */
    public function buscarLogradouroPorIdBairro($palavraChave, $id_bairro) {
        $dal = new CepDAL();
        return $dal->buscarLogradouroPorIdBairro($palavraChave, $id_bairro);
    }

    /**
     * @param string $palavraChave
     * @param string $bairro
     * @param string $cidade
     * @param string $uf
     * @return EnderecoInfo[]
     * @throws Exception
     */
    public function buscarLogradouro($palavraChave, $bairro, $cidade, $uf) {
        $dal = new CepDAL();
        return $dal->buscarLogradouro($palavraChave, $bairro, $cidade, $uf);
    }

    /**
     * @param int $id_cidade
     * @return CidadeInfo
     * @throws Exception
     */
    public function pegarCidade($id_cidade) {
        $dal = new CepDAL();
        return $dal->pegarCidade($id_cidade);
    }

    /**
     * @throws Exception
     * @param string $uf
     * @return EnderecoInfo
     */
    public function pegarEnderecoAleatorio($uf) {
        $dal = new CepDAL();
        return $dal->pegarEnderecoAleatorio($uf);
    }
}