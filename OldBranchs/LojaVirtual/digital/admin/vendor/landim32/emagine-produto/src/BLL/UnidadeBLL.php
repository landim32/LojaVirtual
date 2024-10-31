<?php
namespace Emagine\Produto\BLL;

use Exception;
use Landim32\EasyDB\DB;
use Emagine\Produto\DAL\UnidadeDAL;
use Emagine\Produto\Model\UnidadeInfo;

class UnidadeBLL {

    /**
     * @return bool
     */
    public static function usaUnidadeRoute() {
        if (defined("USA_PRODUTO_UNIDADE_ROUTE")) {
            return (USA_PRODUTO_UNIDADE_ROUTE == true);
        }
        return true;
    }

	/**
     * @throws Exception
     * @param int $id_loja
	 * @return UnidadeInfo[]
	 */
	public function listar($id_loja) {
		$dal = new UnidadeDAL();
		return $dal->listar($id_loja);
	}

	/**
     * @throws Exception
	 * @param int $id_unidade
	 * @return UnidadeInfo
	 */
	public function pegar($id_unidade) {
		$dal = new UnidadeDAL();
		return $dal->pegar($id_unidade);
	}

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $slug
     * @return UnidadeInfo
     */
    public function pegarPorSlug($id_loja, $slug) {
        $dal = new UnidadeDAL();
        return $dal->pegarPorSlug($id_loja, $slug);
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param string $nome
     * @return UnidadeInfo
     */
    public function pegarPorNome($id_loja, $nome) {
        $dal = new UnidadeDAL();
        return $dal->pegarPorNome($id_loja, $nome);
    }

    /**
     * @param array<string,string> $postData
     * @param UnidadeInfo $unidade
     */
    public function pegarDoPost($postData, &$unidade = null) {
        if (is_null($unidade)) {
            $unidade = new UnidadeInfo();
        }
        if (array_key_exists("id_unidade", $postData)) {
            $unidade->setId($postData["id_unidade"]);
        }
        if (array_key_exists("slug", $postData)) {
            $unidade->setSlug($postData["slug"]);
        }
        if (array_key_exists("nome", $postData)) {
            $unidade->setNome($postData["nome"]);
        }
    }

    /**
     * @throws Exception
     * @param int $id_loja
     * @param int $id_unidade
     * @param string $slug
     * @return string
     */
    private function slugValido($id_loja, $id_unidade, $slug)
    {
        $slug = strtolower($slug);
        $loja = $this->pegarPorSlug($id_loja, $slug);
        if (!is_null($loja) && $loja->getId() != $id_unidade) {
            $out = array();
            preg_match_all("#(.*?)-(\d{1,2})#i", $slug, $out, PREG_PATTERN_ORDER);
            if (!isNullOrEmpty($out[1][0]) && is_numeric($out[2][0])) {
                $slug = $this->slugValido($id_loja, $id_unidade, $out[1][0] . "-" . (intval($out[2][0]) + 1));
            }
            else {
                $slug = $this->slugValido($id_loja, $id_unidade, $slug . '-2');
            }
        }
        return $slug;
    }

	/**
	 * @throws Exception
	 * @param UnidadeInfo $unidade
	 */
	protected function validar(&$unidade) {
		if (isNullOrEmpty($unidade->getNome())) {
			throw new Exception('Preencha o nome.');
		}
        $slug = sanitize_slug(strtolower(trim($unidade->getNome())));
        $unidade->setSlug($this->slugValido($unidade->getIdLoja(), $unidade->getId(), $slug));
	}

	/**
	 * @throws Exception
	 * @param UnidadeInfo $unidade
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($unidade, $transaction = true) {
		$this->validar($unidade);
		$dal = new UnidadeDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$id_unidade = $dal->inserir($unidade);
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
	 * @param UnidadeInfo $unidade
	 * @param bool $transaction
	 */
	public function alterar($unidade, $transaction = true) {
		$this->validar($unidade);
		$dal = new UnidadeDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->alterar($unidade);
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
	 * @param int $id_unidade
	 * @param bool $transaction
	 */
	public function excluir($id_unidade, $transaction = true) {
		$dal = new UnidadeDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_unidade);
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

