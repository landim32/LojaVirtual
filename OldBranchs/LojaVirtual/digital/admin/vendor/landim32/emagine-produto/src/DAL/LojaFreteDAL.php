<?php

namespace Emagine\Produto\DAL;

use PDO;
use Exception;
use PDOStatement;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\LojaFreteInfo;

/**
 * Class LojaDAL
 * @package Emagine\Produto\DAL
 * @tablename loja
 * @author EmagineCRUD
 */
class LojaFreteDAL {

	/**
	 * @return string
	 */
	private function query() {
		return "
			SELECT 
                loja_frete.id_frete,
                loja_frete.id_loja,
                loja_frete.uf,
                loja_frete.cidade,
                loja_frete.bairro,
                loja_frete.logradouro,
                loja_frete.valor_frete,
                loja_frete.entrega
			FROM loja_frete
		";
	}

	/**
     * @throws Exception
     * @param int $id_loja
	 * @return LojaFreteInfo[]
	 */
	public function listar($id_loja) {
		$query = $this->query() . "
		    WHERE loja_frete.id_loja = :id_loja
		    ORDER BY 
		        loja_frete.uf,
                loja_frete.cidade,
                loja_frete.bairro,
                loja_frete.logradouro
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
		return DB::getResult($db,"Emagine\\Produto\\Model\\LojaFreteInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 * @return LojaFreteInfo
	 */
	public function pegar($id_frete) {
		$query = $this->query() . "
			WHERE loja_frete.id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Produto\\Model\\LojaFreteInfo");
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
        $query = $this->query() . "
			WHERE loja_frete.id_loja = :id_loja
	    ";
        if (!isNullOrEmpty($uf)) {
            $query .= " AND loja_frete.uf = :uf ";
        }
        if (!isNullOrEmpty($cidade)) {
            $query .= " AND loja_frete.cidade = :cidade ";
        }
        if (!isNullOrEmpty($bairro)) {
            $query .= " AND loja_frete.bairro = :bairro ";
        }
        if (!isNullOrEmpty($logradouro)) {
            $query .= " AND loja_frete.logradouro = :logradouro ";
        }
        $query .= "
		    ORDER BY 
		        loja_frete.uf,
                loja_frete.cidade,
                loja_frete.bairro,
                loja_frete.logradouro
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        if (!isNullOrEmpty($uf)) {
            $db->bindValue(":uf", $uf);
        }
        if (!isNullOrEmpty($cidade)) {
            $db->bindValue(":cidade", $cidade);
        }
        if (!isNullOrEmpty($bairro)) {
            $db->bindValue(":bairro", $bairro);
        }
        if (!isNullOrEmpty($logradouro)) {
            $db->bindValue(":logradouro", $logradouro);
        }
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\LojaFreteInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param LojaFreteInfo $frete
	 */
	public function preencherCampo(PDOStatement $db, LojaFreteInfo $frete) {
        $db->bindValue(":uf", $frete->getUf());
        $db->bindValue(":cidade", $frete->getCidade());
        $db->bindValue(":bairro", $frete->getBairro());
        $db->bindValue(":logradouro", $frete->getLogradouro());
        $db->bindValue(":valor_frete", $frete->getValorFrete());
        $db->bindValue(":entrega", $frete->getEntrega(), PDO::PARAM_BOOL);
	}

	/**
     * @throws Exception
	 * @param LojaFreteInfo $frete
	 * @return int
	 */
	public function inserir($frete) {
		$query = "
			INSERT INTO loja_frete (
                id_loja,
                uf,
                cidade,
                bairro,
                logradouro,
                valor_frete,
                entrega
			) VALUES (
			    :id_loja,
                :uf,
                :cidade,
                :bairro,
                :logradouro,
                :valor_frete,
                :entrega
			)
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $frete->getIdLoja(), PDO::PARAM_INT);
		$this->preencherCampo($db, $frete);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param LojaFreteInfo $frete
	 */
	public function alterar($frete) {
		$query = "
			UPDATE loja_frete SET 
                uf = :uf,
                cidade = :cidade,
                bairro = :bairro,
                logradouro = :logradouro,
                valor_frete = :valor_frete,
                entrega = :entrega
			WHERE loja_frete.id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $frete);
        $db->bindValue(":id_frete", $frete->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_frete
	 */
	public function excluir($id_frete) {
		$query = "
			DELETE FROM loja_frete
			WHERE id_frete = :id_frete
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_frete", $id_frete, PDO::PARAM_INT);
		$db->execute();
	}
}

