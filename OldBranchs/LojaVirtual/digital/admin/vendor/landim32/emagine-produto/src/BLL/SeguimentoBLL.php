<?php
namespace Emagine\Produto\BLL;

use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\DAL\SeguimentoDAL;
use Emagine\Produto\Model\SeguimentoInfo;

class SeguimentoBLL {

    /**
     * @return bool
     */
    public static function usaSeguimentoRoute() {
        if (defined("USA_PRODUTO_SEGUIMENTO_ROUTE")) {
            return (USA_PRODUTO_SEGUIMENTO_ROUTE == true);
        }
        return true;
    }

	/**
     * @throws Exception
	 * @return SeguimentoInfo[]
	 */
	public function listar() {
		$dal = new SeguimentoDAL();
		return $dal->listar();
	}

    /**
     * @throws Exception
     * @return SeguimentoInfo[]
     */
    public function listarComLoja() {
        $dal = new SeguimentoDAL();
        return $dal->listarComLoja();
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param int $raio
     * @return SeguimentoInfo[]
     * @throws Exception
     */
    public function buscarPorPosicao($latitude, $longitude, $raio = 100) {
        $dal = new SeguimentoDAL();
        return $dal->buscarPorPosicao($latitude, $longitude, $raio);
    }

	/**
     * @throws Exception
	 * @param int $id_seguimento
	 * @return SeguimentoInfo
	 */
	public function pegar($id_seguimento) {
		$dal = new SeguimentoDAL();
		return $dal->pegar($id_seguimento);
	}

    /**
     * @throws Exception
     * @param string $slug
     * @return SeguimentoInfo
     */
    public function pegarPorSlug($slug) {
        $dal = new SeguimentoDAL();
        return $dal->pegarPorSlug($slug);
    }

    /**
     * @param array<string,string> $postData
     * @param SeguimentoInfo $seguimento
     */
    public function pegarDoPost($postData, &$seguimento = null) {
        if (is_null($seguimento)) {
            $seguimento = new SeguimentoInfo();
        }
        if (array_key_exists("apenas_pj", $postData)) {
            $seguimento->setApenasPJ($postData["apenas_pj"] == "1");
        }
        else {
            $seguimento->setApenasPJ(false);
        }
        if (array_key_exists("id_unidade", $postData)) {
            $seguimento->setId($postData["id_unidade"]);
        }
        if (array_key_exists("slug", $postData)) {
            $seguimento->setSlug($postData["slug"]);
        }
        if (array_key_exists("nome", $postData)) {
            $seguimento->setNome($postData["nome"]);
        }
    }

    /**
     * @throws Exception
     * @param int $id_seguimento
     * @param string $slug
     * @return string
     */
    private function slugValido($id_seguimento, $slug)
    {
        $slug = strtolower($slug);
        $loja = $this->pegarPorSlug($slug);
        if (!is_null($loja) && $loja->getId() != $id_seguimento) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_seguimento, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_seguimento, $slug . '-2');
            }
        }
        return $slug;
    }

	/**
	 * @throws Exception
	 * @param SeguimentoInfo $seguimento
	 */
	protected function validar(&$seguimento) {
		if (isNullOrEmpty($seguimento->getNome())) {
			throw new Exception('Preencha o nome.');
		}
        $slug = sanitize_slug(strtolower(trim($seguimento->getNome())));
        $seguimento->setSlug($this->slugValido($seguimento->getId(), $slug));
	}

	/**
	 * @throws Exception
	 * @param SeguimentoInfo $seguimento
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($seguimento, $transaction = true) {
		$this->validar($seguimento);
		$dal = new SeguimentoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_unidade = $dal->inserir($seguimento);
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
		return $id_unidade;
	}

	/**
	 * @throws Exception
	 * @param SeguimentoInfo $seguimento
	 * @param bool $transaction
	 */
	public function alterar($seguimento, $transaction = true) {
		$this->validar($seguimento);
		$dal = new SeguimentoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($seguimento);
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
	 * @param int $id_seguimento
	 * @param bool $transaction
	 */
	public function excluir($id_seguimento, $transaction = true) {
		$dal = new SeguimentoDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_seguimento);
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
}

