<?php
namespace Emagine\Banner\DAL;

use Emagine\Produto\DAL\LojaDAL;
use Emagine\Produto\Model\LojaInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Banner\Model\BannerInfo;

/**
 * Class BannerDAL
 * @package Emagine\Banner\DAL
 * @tablename banner
 * @author EmagineCRUD
 */
class BannerDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT 
				banner.id_banner,
				banner.cod_tipo,
				banner.slug,
				banner.nome,
				banner.largura,
				banner.altura,
				banner.quantidade_loja,
                banner.pageview
			FROM banner
		";
	}

	/**
     * @throws Exception
     * @param int $cod_tipo
	 * @return BannerInfo[]
	 */
	public function listar($cod_tipo) {
		$query = $this->query() . " WHERE (1=1) ";
        if ($cod_tipo > 0) {
            $query .= " AND banner.cod_tipo = :cod_tipo ";
        }
        $query .= " ORDER BY banner.nome ";
		$db = DB::getDB()->prepare($query);
        if ($cod_tipo > 0) {
            $db->bindValue(":cod_tipo", $cod_tipo, PDO::PARAM_INT);
        }
		return DB::getResult($db,"Emagine\\Banner\\Model\\BannerInfo");
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @return BannerInfo[]
     */
    public function listarPorLoja($id_loja) {
        $query = $this->query() . "
			WHERE banner.id_loja = :id_loja
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_loja", $id_loja, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Banner\\Model\\BannerInfo");
    }

    /**
     * @throws Exception
     * @param int $id_banner
     * @return LojaInfo[]
     */
    public function listarLojaPorBanner($id_banner) {
        $dal = new LojaDAL();
        $query = $dal->query(true) . "
            INNER JOIN banner_peca ON banner_peca.id_loja = loja.id_loja
            WHERE banner_peca.id_banner = :id_banner
            ORDER BY loja.nome
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
        return DB::getResult($db,"Emagine\\Produto\\Model\\LojaInfo");
    }

	/**
     * @throws Exception
	 * @param int $id_banner
	 * @return BannerInfo
	 */
	public function pegar($id_banner) {
		$query = $this->query() . "
			WHERE banner.id_banner = :id_banner
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Banner\\Model\\BannerInfo");
	}

    /**
     * @throws Exception
     * @param int $slug
     * @return BannerInfo
     */
    public function pegarPorSlug($slug) {
        $query = $this->query() . "
			WHERE banner.slug = :slug
			LIMIT 1
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":slug", $slug);
        return DB::getValueClass($db,"Emagine\\Banner\\Model\\BannerInfo");
    }

	/**
	 * @param PDOStatement $db
	 * @param BannerInfo $banner
	 */
	public function preencherCampo(PDOStatement $db, BannerInfo $banner) {
        $db->bindValue(":cod_tipo", $banner->getCodTipo(), PDO::PARAM_INT);
		$db->bindValue(":slug", $banner->getSlug());
		$db->bindValue(":nome", $banner->getNome());
		$db->bindValue(":largura", $banner->getLargura(), PDO::PARAM_INT);
		$db->bindValue(":altura", $banner->getAltura(), PDO::PARAM_INT);
        $db->bindValue(":quantidade_loja", $banner->getQuantidadeLoja(), PDO::PARAM_INT);
	}

	/**
     * @throws Exception
	 * @param BannerInfo $banner
	 * @return int
	 */
	public function inserir($banner) {
		$query = "
			INSERT INTO banner (
			    cod_tipo,
				slug,
				nome,
				largura,
				altura,
				quantidade_loja
			) VALUES (
			    :cod_tipo,
				:slug,
				:nome,
				:largura,
				:altura,
				:quantidade_loja
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $banner);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param BannerInfo $banner
	 */
	public function alterar($banner) {
		$query = "
			UPDATE banner SET
			    cod_tipo = :cod_tipo, 
				slug = :slug,
				nome = :nome,
				largura = :largura,
				altura = :altura,
				quantidade_loja = :quantidade_loja
			WHERE banner.id_banner = :id_banner
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $banner);
		$db->bindValue(":id_banner", $banner->getId(), PDO::PARAM_INT);
		$db->execute();
	}

	/**
     * @throws Exception
	 * @param int $id_banner
	 */
	public function excluir($id_banner) {
		$query = "
			DELETE FROM banner
			WHERE id_banner = :id_banner
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
		$db->execute();
	}

    /**
     * @throws Exception
     * @param int $id_banner
     * @return int
     */
    public function pegarPageview($id_banner) {
        $query = "
            SELECT banner.pageview
            FROM banner 
            WHERE banner.id_banner = :id_banner
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
        return DB::getValue($db,"pageview");
    }

    /**
     * @throws Exception
     * @param int $id_banner
     */
    public function contarPageview($id_banner) {
        $query = "
			UPDATE banner SET
			    pageview = IFNULL(pageview, 0) + 1
			WHERE id_banner = :id_banner
		";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":id_banner", $id_banner, PDO::PARAM_INT);
        $db->execute();
    }
}

