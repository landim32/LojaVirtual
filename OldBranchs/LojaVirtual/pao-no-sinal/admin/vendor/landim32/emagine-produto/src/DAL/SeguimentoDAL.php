<?php
namespace Emagine\Produto\DAL;

use Emagine\Produto\Model\LojaInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\Model\SeguimentoInfo;

/**
 * Class ProdutoCategoriaDAL
 * @package EmagineProduto\DAL
 * @tablename produto_categoria
 * @author EmagineCRUD
 */
class SeguimentoDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT DISTINCT
				loja_seguimento.id_seguimento,
			    loja_seguimento.apenas_pj,
				loja_seguimento.slug,
				loja_seguimento.nome,
				loja_seguimento.icone
			FROM loja_seguimento
		";
	}

	/**
     * @throws Exception
	 * @return SeguimentoInfo[]
	 */
	public function listar() {
		$query = $this->query() . "  
		    ORDER BY loja_seguimento.nome
		";
		$db = DB::getDB()->prepare($query);
		return DB::getResult($db,"Emagine\\Produto\\Model\\SeguimentoInfo");
	}

    /**
     * @throws Exception
     * @return SeguimentoInfo[]
     */
    public function listarComLoja() {
        $query = $this->query() . "  
            WHERE loja_seguimento.id_seguimento IN (
                SELECT loja.id_seguimento
                FROM loja
                WHERE loja.cod_situacao = 1
            )
		    ORDER BY loja_seguimento.nome
		";
        $db = DB::getDB()->prepare($query);
        return DB::getResult($db,"Emagine\\Produto\\Model\\SeguimentoInfo");
    }

    /**
     * @throws Exception
     * @param float $latitude
     * @param float $longitude
     * @param int $raio
     * @return SeguimentoInfo[]
     */
    public function buscarPorPosicao($latitude, $longitude, $raio) {
        $query = $this->query() . "
            INNER JOIN loja ON loja.id_seguimento = loja_seguimento.id_seguimento  
            WHERE loja.cod_situacao = :cod_situacao
		";
        if ($raio > 0) {
            $query .= "
                AND ROUND(
                    (
                        (
                            ACOS(
                                SIN(loja.latitude * PI() / 180) * SIN(:latitude1 * PI() / 180) + 
                                COS(loja.latitude * PI() / 180) * COS(:latitude2 * PI() / 180) * 
                                COS((loja.longitude - :longitude1) * PI() / 180)
                            ) * 180 / PI()
                        ) * 60 * 1.1515
                    ) * 1.609344
                ) <= :raio
            ";
        }
        $query .= " ORDER BY loja_seguimento.nome ";
        //echo $query;
        $db = DB::getDB()->prepare($query);
        if ($raio > 0) {
            $db->bindValue(":latitude1", $latitude);
            $db->bindValue(":latitude2", $latitude);
            $db->bindValue(":longitude1", $longitude);
            $db->bindValue(":raio", $raio, PDO::PARAM_INT);
        }
        //var_dump($query, $latitude, $longitude, $raio);
        $db->bindValue(":cod_situacao", LojaInfo::ATIVO);
        return DB::getResult($db,"Emagine\\Produto\\Model\\SeguimentoInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_seguimento
	 * @return SeguimentoInfo
	 */
	public function pegar($id_seguimento) {
		$query = $this->query() . "
			WHERE loja_seguimento.id_seguimento = :id_seguimento
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_seguimento", $id_seguimento, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Produto\\Model\\SeguimentoInfo");
	}

    /**
     * @throws Exception
     * @param string $slug
     * @return SeguimentoInfo
     */
    public function pegarPorSlug($slug) {
        $query = $this->query() . "
			WHERE loja_seguimento.slug = :slug
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Produto\\Model\\SeguimentoInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param SeguimentoInfo $seguimento
	 */
	public function preencherCampo(PDOStatement $db, SeguimentoInfo $seguimento) {
        $db->bindValue(":apenas_pj", $seguimento->getApenasPJ() ? 1 : 0, PDO::PARAM_INT);
        $db->bindValue(":slug", $seguimento->getSlug());
        $db->bindValue(":icone", $seguimento->getIcone());
		$db->bindValue(":nome", $seguimento->getNome());
	}

	/**
     * @throws Exception
	 * @param SeguimentoInfo $seguimento
	 * @return int
	 */
	public function inserir($seguimento) {
		$query = "
			INSERT INTO loja_seguimento (
                apenas_pj,
				slug,
				nome,
				icone
			) VALUES (
			    :apenas_pj,
			    :slug,
				:nome,
				:icone
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $seguimento);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param SeguimentoInfo $seguimento
	 */
	public function alterar($seguimento) {
		$query = "
			UPDATE loja_seguimento SET 
			    apenas_pj = :apenas_pj,
			    slug = :slug,
			    nome = :nome,
			    icone = :icone
			WHERE id_seguimento = :id_seguimento
		";
		$db = DB::getDB()->prepare($query);
        $db->bindValue(":id_seguimento", $seguimento->getId(), PDO::PARAM_INT);
		$this->preencherCampo($db, $seguimento);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_seguimento
	 */
	public function excluir($id_seguimento) {
		$query = "
			DELETE FROM loja_seguimento
			WHERE id_seguimento = :id_seguimento
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_seguimento", $id_seguimento, PDO::PARAM_INT);
		$db->execute();
	}
}

