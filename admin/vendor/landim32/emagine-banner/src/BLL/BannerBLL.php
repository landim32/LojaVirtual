<?php
/**
 * Created by EmagineCRUD Creator.
 * User: Rodrigo Landim
 * Date: 13/10/2018
 * Time: 18:01
 * Tablename: banner
 */

namespace Emagine\Banner\BLL;

use Emagine\Produto\Model\LojaInfo;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Banner\DAL\BannerDAL;
use Emagine\Banner\Model\BannerInfo;

/**
 * Class BannerBLL
 * @package Emagine\Banner\BLL
 * @tablename banner
 * @author EmagineCRUD
 */
class BannerBLL {

	/**
     * @throws Exception
     * @param int $cod_tipo
	 * @return BannerInfo[]
	 */
	public function listar($cod_tipo = 0) {
		$dal = new BannerDAL();
		return $dal->listar($cod_tipo);
	}


    /**
     * @throws Exception
     * @param int $id_banner
     * @return LojaInfo[]
     */
    public function listarLojaPorBanner($id_banner) {
        $dal = new BannerDAL();
        return $dal->listarLojaPorBanner($id_banner);
    }

    /**
     * @return string[]
     */
	public function listarTipo() {
	    return array(
	        BannerInfo::NORMAL => "Normal",
            BannerInfo::ADMIN => "Administrativo"
        );
    }

	/**
     * @throws Exception
	 * @param int $id_banner
	 * @return BannerInfo
	 */
	public function pegar($id_banner) {
		$dal = new BannerDAL();
		return $dal->pegar($id_banner);
	}

    /**
     * @throws Exception
     * @param string $slug
     * @return BannerInfo
     */
    public function pegarPorSlug($slug) {
        $dal = new BannerDAL();
        return $dal->pegarPorSlug($slug);
    }

	/**
	 * @param string[] $postData
	 * @param BannerInfo|null $banner
	 * @return BannerInfo
	 */
	public function pegarDoPost($postData, $banner = null) {
		if (is_null($banner)) {
			$banner = new BannerInfo();
		}
		if (array_key_exists("id_banner", $postData)) {
			$banner->setId(intval($postData["id_banner"]));
		}
        if (array_key_exists("cod_tipo", $postData)) {
            $banner->setCodTipo(intval($postData["cod_tipo"]));
        }
		if (array_key_exists("slug", $postData)) {
			$banner->setSlug($postData["slug"]);
		}
		if (array_key_exists("nome", $postData)) {
			$banner->setNome($postData["nome"]);
		}
		if (array_key_exists("largura", $postData)) {
			$banner->setLargura(intval($postData["largura"]));
		}
		if (array_key_exists("altura", $postData)) {
			$banner->setAltura(intval($postData["altura"]));
		}
        if (array_key_exists("quantidade_loja", $postData)) {
            $banner->setQuantidadeLoja(intval($postData["quantidade_loja"]));
        }
		return $banner;
	}

    /**
     * @throws Exception
     * @param int $id_banner
     * @param string $slug
     * @return string
     */
    private function slugValido($id_banner, $slug)
    {
        $slug = strtolower($slug);
        $loja = $this->pegarPorSlug($slug);
        if (!is_null($loja) && $loja->getId() != $id_banner) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_banner, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_banner, $slug . '-2');
            }
        }
        return $slug;
    }

	/**
	 * @throws Exception
	 * @param BannerInfo $banner
	 */
	protected function validar(&$banner) {
		if (isNullOrEmpty($banner->getNome())) {
			throw new Exception('Preencha o nome.');
		}
        if (!($banner->getCodTipo() > 0)) {
            throw new Exception('Selecione o tipo de banner.');
        }
        if (!($banner->getLargura() > 0)) {
            throw new Exception('Preencha a largura.');
        }
        if (!($banner->getAltura() > 0)) {
            throw new Exception('Preencha a altura.');
        }
        $slug = sanitize_slug(strtolower(trim($banner->getNome())));
        $banner->setSlug($this->slugValido($banner->getId(), $slug));
	}

	/**
	 * @throws Exception
	 * @param BannerInfo $banner
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($banner, $transaction = true) {
		$this->validar($banner);
		$dal = new BannerDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$idBanner = $dal->inserir($banner);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
		return $idBanner;
	}

	/**
	 * @throws Exception
	 * @param BannerInfo $banner
	 * @param bool $transaction
	 */
	public function alterar($banner, $transaction = true) {
		$this->validar($banner);
		$dal = new BannerDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($banner);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

	/**
	 * @throws Exception
	 * @param int $id_banner
	 * @param bool $transaction
	 */
	public function excluir($id_banner, $transaction = true) {
		$dal = new BannerDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_banner);
			if ($transaction === true) {
				DB::commit();
			}
		}
		catch (Exception $e){
			if ($transaction === true) {
				DB::rollBack();
			}
			throw $e;
		}
	}

    /**
     * @param string $name
     * @param string $className
     * @param string|null $selecionado
     * @return string
     */
    public function dropdownListCodTipo($name, $className = "form-control", $selecionado = null) {
        $str = "";
        $str .= "<select name='" . $name . "' class='" . $className . "'>\n";
        foreach ($this->listarTipo() as $key => $value) {
            $str .= "<option value=\"" . $key . "\"";
            if ($key == $selecionado) {
                $str .= " selected=\"selected\"";
            }
            $str .= ">" . $value . "</option>\n";
        }
        $str .= "</select>";
        return $str;
    }

}

