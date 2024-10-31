<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 15/11/2017
 * Time: 19:27
 * Tablename: bairro
 */

namespace Emagine\Endereco\DAL;

use PDO;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Endereco\Model\UfInfo;
use Emagine\Endereco\Model\CidadeInfo;
use Emagine\Endereco\Model\BairroInfo;
use Emagine\Endereco\Model\EnderecoInfo;

class CepDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
            SELECT
	            cepbr_endereco.cep,
                CONCAT(cepbr_endereco.tipo_logradouro, ' ', cepbr_endereco.logradouro) as 'logradouro',
                cepbr_endereco.complemento,
                cepbr_bairro.bairro,
                cepbr_cidade.cidade,
                cepbr_cidade.uf,
                cepbr_geo.latitude,
                cepbr_geo.longitude
            FROM cepbr_endereco
            LEFT JOIN cepbr_bairro ON cepbr_bairro.id_bairro = cepbr_endereco.id_bairro
            LEFT JOIN cepbr_cidade ON cepbr_cidade.id_cidade = cepbr_endereco.id_cidade
            LEFT JOIN cepbr_geo ON cepbr_geo.cep = cepbr_endereco.cep
		";
	}

    /**
     * @return string
     */
    public function queryPosicao() {
        return "
            SELECT
	            cepbr_geo.cep,
                ROUND(
                  (
                    (
                      ACOS(
                        SIN(cepbr_geo.latitude * PI() / 180) * SIN(:latitude1 * PI() / 180) + 
                        COS(cepbr_geo.latitude * PI() / 180) * COS(:latitude2 * PI() / 180) * 
                        COS((cepbr_geo.longitude - :longitude1) * PI() / 180)
                      ) * 180 / PI()
                    ) * 60 * 1.1515
                  ) * 1.609344 * 1000
                ) AS 'distancia'
            FROM cepbr_geo
		";
    }

	/**
     * @throws Exception
     * @param string $cep
	 * @return EnderecoInfo
	 */
	public function pegarPorCep($cep) {
		$query = $this->query() . "
		    WHERE cepbr_endereco.cep = :cep
		    LIMIT 1
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":cep", $cep);
		return DB::getValueClass($db,"Emagine\\Endereco\\Model\\EnderecoInfo");
	}

    /**
     * @throws Exception
     * @param string $uf
     * @return EnderecoInfo
     */
    public function pegarEnderecoAleatorio($uf) {
        $query = $this->query() . "
		    WHERE cepbr_cidade.uf = :uf
		    ORDER BY RAND()
		    LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":uf", $uf);
        return DB::getValueClass($db,"Emagine\\Endereco\\Model\\EnderecoInfo");
    }

    /**
     * @throws Exception
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    public function pegarCepMaisProximo($latitude, $longitude) {
        $query = $this->queryPosicao() . "
		    ORDER BY distancia
		    LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":latitude1", $latitude);
        $db->bindValue(":latitude2", $latitude);
        $db->bindValue(":longitude1", $longitude);
        return DB::getValue($db,"cep");
    }

    /**
     * @throws Exception
     * @param string $uf
     * @return CidadeInfo
     */
    public function pegarCidadeAleatoria($uf) {
        $query = "
            SELECT
                cepbr_cidade.id_cidade,
                cepbr_cidade.cidade as 'nome',
                cepbr_cidade.uf
            FROM cepbr_cidade
		    WHERE cepbr_cidade.uf = :uf
		    AND cepbr_cidade.id_cidade IN (
		        SELECT cepbr_bairro.id_cidade
		        FROM cepbr_bairro
		    )
		    AND cepbr_cidade.id_cidade IN (
		        SELECT cepbr_endereco.id_cidade
		        FROM cepbr_endereco
		    )
		    ORDER BY RAND()
		    LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":uf", $uf);
        return DB::getValueClass($db,"Emagine\\Endereco\\Model\\CidadeInfo");
    }

    /**
     * @return UfInfo[]
     * @throws Exception
     */
    public function listarUf() {
        $query = "
            SELECT
                cepbr_estado.uf,
                cepbr_estado.estado as 'nome'
            FROM cepbr_estado
		    ORDER BY
		        cepbr_estado.estado
		        
		";
        $db = DB::getDB()->prepare($query);
        return DB::getResult($db,"Emagine\\Endereco\\Model\\UfInfo");
    }

    /**
     * @param string $palavraChave
     * @param string $uf
     * @return CidadeInfo[]
     * @throws Exception
     */
    public function buscarCidadePorUf($palavraChave, $uf) {
        $query = "
            SELECT
                cepbr_cidade.id_cidade,
                cepbr_cidade.cidade as 'nome',
                cepbr_cidade.uf
            FROM cepbr_cidade
		    WHERE cepbr_cidade.uf = :uf
		    AND cepbr_cidade.cidade LIKE :palavrachave
		    ORDER BY
		        cepbr_cidade.cidade
		        
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavrachave", $palavraChave . "%");
        $db->bindValue(":uf", $uf);
        return DB::getResult($db,"Emagine\\Endereco\\Model\\CidadeInfo");
    }

    /**
     * @param string $palavraChave
     * @param int $id_cidade
     * @return BairroInfo[]
     * @throws Exception
     */
    public function buscarBairroPorIdCidade($palavraChave, $id_cidade) {
        $query = "
            SELECT
                cepbr_bairro.id_bairro,
                cepbr_bairro.bairro as 'nome',
                cepbr_bairro.id_cidade,
                true as 'origem_cep'
            FROM cepbr_bairro
		    WHERE cepbr_bairro.id_cidade = :id_cidade
		    AND cepbr_bairro.bairro LIKE :palavrachave
		    ORDER BY
		        cepbr_bairro.bairro
		    LIMIT 10
		        
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavrachave", "%" . $palavraChave . "%");
        $db->bindValue(":id_cidade", $id_cidade, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Endereco\\Model\\BairroInfo");
    }

    /**
     * @throws Exception
     * @param string $palavraChave
     * @param string $uf
     * @param string $cidade
     * @return BairroInfo[]
     */
    public function buscarBairro($palavraChave, $uf, $cidade) {
        $query = "
            SELECT
                cepbr_bairro.id_bairro,
                cepbr_bairro.bairro as 'nome',
                cepbr_bairro.id_cidade,
                true as 'origem_cep'
            FROM cepbr_bairro
            INNER JOIN cepbr_cidade ON cepbr_cidade.id_cidade = cepbr_bairro.id_cidade
            WHERE cepbr_cidade.uf = :uf 
            AND cepbr_cidade.cidade = :cidade 
            AND cepbr_bairro.bairro LIKE :palavrachave
		    ORDER BY
		        cepbr_bairro.bairro
		    LIMIT 10
		        
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavrachave", "%" . $palavraChave . "%");
        $db->bindValue(":uf", $uf);
        $db->bindValue(":cidade", $cidade);
        return DB::getResult($db,"Emagine\\Endereco\\Model\\BairroInfo");
    }

    /**
     * @param string $palavraChave
     * @param int $id_bairro
     * @return EnderecoInfo[]
     * @throws Exception
     */
    public function buscarLogradouroPorIdBairro($palavraChave, $id_bairro) {
        $query = $this->query() . "
		    WHERE cepbr_endereco.logradouro LIKE :palavrachave
		    AND cepbr_endereco.id_bairro LIKE :id_bairro
		    LIMIT 10
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavrachave", "%" . $palavraChave . "%");
        $db->bindValue(":id_bairro", $id_bairro, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Endereco\\Model\\EnderecoInfo");
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
        $query = $this->query() . "
            WHERE cepbr_cidade.uf = :uf 
            AND cepbr_cidade.cidade = :cidade 
            AND cepbr_bairro.bairro = :bairro
            AND cepbr_endereco.logradouro LIKE :palavrachave
		    LIMIT 10
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":palavrachave", "%" . $palavraChave . "%");
        $db->bindValue(":uf", $uf);
        $db->bindValue(":cidade", $cidade);
        $db->bindValue(":bairro", $bairro);
        return DB::getResult($db,"Emagine\\Endereco\\Model\\EnderecoInfo");
    }

    /**
     * @param int $id_cidade
     * @return CidadeInfo
     * @throws Exception
     */
    public function pegarCidade($id_cidade) {
        $query = "
            SELECT
                cepbr_cidade.id_cidade,
                cepbr_cidade.cidade as 'nome',
                cepbr_cidade.uf
            FROM cepbr_cidade
		    WHERE cepbr_cidade.id_cidade = :id_cidade
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_cidade", $id_cidade);
        return DB::getValueAsClass($db,"Emagine\\Endereco\\Model\\CidadeInfo");
    }



}

