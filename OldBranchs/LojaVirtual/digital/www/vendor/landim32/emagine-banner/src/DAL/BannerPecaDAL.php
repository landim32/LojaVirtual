<?php
namespace Emagine\Banner\DAL;

use Emagine\Banner\Model\BannerFiltroInfo;
use Emagine\Produto\DAL\LojaDAL;
use Emagine\Produto\Model\LojaInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Banner\Model\BannerPecaInfo;

/**
 * Class BannerPecaDAL
 * @package Emagine\Banner\DAL
 * @tablename banner_peca
 * @author EmagineCRUD
 */
class BannerPecaDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				banner_peca.id_peca,
				banner_peca.id_banner,
				banner_peca.id_loja,
			    banner_peca.id_loja_destino,
				banner_peca.id_produto,
			    banner_peca.cod_destino,
				banner_peca.nome,
				banner_peca.nome_arquivo,
				banner_peca.data_inclusao,
				banner_peca.ultima_alteracao,
				banner_peca.url,
				banner_peca.ordem,
				banner_peca.pageview
			FROM banner_peca
		";
	}

	/**
     * @throws Exception
     * @param BannerFiltroInfo $filtro
	 * @return BannerPecaInfo[]
	 */
	public function listar(BannerFiltroInfo $filtro) {
        $query = $this->query();
        if (!is_null($filtro->getIdSeguimento()) || (!is_null($filtro->getLatitude()) && !is_null($filtro->getLatitude()))) {
            $query .= " INNER JOIN loja ON loja.id_loja = banner_peca.id_loja ";
        }
        $query .= " WHERE (1=1) ";
        if (!is_null($filtro->getIdBanner())) {
            $query .= " AND banner_peca.id_banner = :id_banner ";
        }
        if (!is_null($filtro->getIdSeguimento())) {
            $query .= " AND loja.id_seguimento = :id_seguimento ";
        }
        if (!is_null($filtro->getIdLoja())) {
            $query .= " AND banner_peca.id_loja = :id_loja ";
        }
        if (!is_null($filtro->getLatitude()) || !is_null($filtro->getLongitude())) {
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
        if ($filtro->getOrdem() == BannerFiltroInfo::ALEATORIO) {
            $query .= " ORDER BY RAND() ";
        }
        else {
            $query .= " ORDER BY banner_peca.ordem ";
        }
		$db = DB::getDB()->prepare($query);
        if (!is_null($filtro->getIdBanner())) {
            $db->bindValue(":id_banner", $filtro->getIdBanner(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getIdSeguimento())) {
            $db->bindValue(":id_seguimento", $filtro->getIdSeguimento(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getIdLoja())) {
            $db->bindValue(":id_loja", $filtro->getIdLoja(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getLatitude()) || !is_null($filtro->getLongitude())) {
            $db->bindValue(":latitude1", $filtro->getLatitude());
            $db->bindValue(":latitude2", $filtro->getLatitude());
            $db->bindValue(":longitude1", $filtro->getLongitude());
            $db->bindValue(":raio", $filtro->getRaio(), PDO::PARAM_INT);
        }
		return DB::getResult($db,"Emagine\\Banner\\Model\\BannerPecaInfo");
	}

	/**
     * @throws Exception
	 * @param int $id_peca
	 * @return BannerPecaInfo
	 */
	public function pegar($id_peca) {
		$query = $this->query() . "
			WHERE banner_peca.id_peca = :id_peca
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_peca", $id_peca, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\Banner\\Model\\BannerPecaInfo");
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_banner
     * @param int $ordem
     * @return BannerPecaInfo
     */
    public function pegarPorOrdem($id_loja, $id_banner, $ordem) {
        $query = $this->query() . "
			WHERE banner_peca.id_loja = :id_loja
			AND banner_peca.id_banner = :id_banner
			AND banner_peca.ordem = :ordem
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        $db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
        $db->bindValue(":ordem", $ordem, PDO::PARAM_INT);
        return DB::getValueClass($db,"Emagine\\Banner\\Model\\BannerPecaInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param BannerPecaInfo $peca
	 */
	public function preencherCampo(PDOStatement $db, BannerPecaInfo $peca) {
        $db->bindValue(":id_banner", $peca->getIdBanner(), PDO::PARAM_INT);
	    if ($peca->getIdLoja() > 0) {
            $db->bindValue(":id_loja", $peca->getIdLoja(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_loja", null);
        }
        if ($peca->getIdLojaDestino() > 0) {
            $db->bindValue(":id_loja_destino", $peca->getIdLojaDestino(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_loja_destino", null);
        }
        if ($peca->getIdProduto() > 0) {
            $db->bindValue(":id_produto", $peca->getIdProduto(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_produto", null);
        }
        $db->bindValue(":cod_destino", $peca->getCodDestino(), PDO::PARAM_INT);
		$db->bindValue(":nome", $peca->getNome());
		$db->bindValue(":nome_arquivo", $peca->getNomeArquivo());
		$db->bindValue(":url", $peca->getUrl());
	}

	/**
     * @throws Exception
	 * @param BannerPecaInfo $peca
	 * @return int
	 */
	public function inserir($peca) {
		$query = "
			INSERT INTO banner_peca (
				id_banner,
				id_loja,
			    id_loja_destino,
				id_produto,
			    cod_destino,
				ordem,
				nome,
				nome_arquivo,
				data_inclusao,
				ultima_alteracao,
				url
			) VALUES (
				:id_banner,
				:id_loja,
			    :id_loja_destino,
				:id_produto,
			    :cod_destino,
				9999,
				:nome,
				:nome_arquivo,
				NOW(),
				NOW(),
				:url
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $peca);
        //$db->bindValue(":id_loja", $peca->getIdLoja(), PDO::PARAM_INT);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param BannerPecaInfo $peca
	 */
	public function alterar($peca) {
		$query = "
			UPDATE banner_peca SET 
				id_banner = :id_banner,
                id_loja = :id_loja,
                id_loja_destino = :id_loja_destino,
				id_produto = :id_produto,
			    cod_destino = :cod_destino,
				nome = :nome,
				nome_arquivo = :nome_arquivo,
				ultima_alteracao = NOW(),
				url = :url
			WHERE banner_peca.id_peca = :id_peca
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $peca);
		$db->bindValue(":id_peca", $peca->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_peca
	 */
	public function excluir($id_peca) {
		$query = "
			DELETE FROM banner_peca
			WHERE id_peca = :id_peca
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_peca", $id_peca, PDO::PARAM_INT);
		$db->execute();
	}
	/**
     * @throws Exception
	 * @param int $id_banner
	 */
	public function limparPorIdBanner($id_banner) {
		$query = "
			DELETE FROM banner_peca
			WHERE id_banner = :id_banner
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_peca
     * @param int $ordem
     */
	public function alterarOrdem($id_peca, $ordem) {
        $query = " 
            UPDATE banner_peca SET
                banner_peca.ordem = :ordem
            WHERE banner_peca.id_peca = :id_peca
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_peca", $id_peca, PDO::PARAM_INT);
        $db->bindValue(":ordem", $ordem, PDO::PARAM_INT);
        $db->execute();
    }

    /**
     * @throws Exception
     * @param int $id_peca
     */
    public function contarPageview($id_peca) {
        $query = "
			UPDATE banner_peca SET
			    pageview = IFNULL(pageview, 0) + 1
			WHERE id_peca = :id_peca
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_peca", $id_peca, PDO::PARAM_INT);
        $db->execute();
    }

}

