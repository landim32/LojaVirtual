<?php
namespace Emagine\Login\BLL;

use Exception;
use Landim32\EasyDB\DB;
use Emagine\Login\Model\PermissaoInfo;

/**
 * Class PermissaoBLL
 * @package EmagineAuth\BLL
 * @tablename permissao
 * @author EmagineCRUD
 */
class PermissaoBLL {

    const GLOBAL_PERMISSAO = '_permissao';

	/**
     * @throws Exception
	 * @return PermissaoInfo[]
	 */
	public function listar() {
	    return $GLOBALS[PermissaoBLL::GLOBAL_PERMISSAO];
	}


	/**
     * @throws Exception
	 * @param string $slug
	 * @return PermissaoInfo
	 */
	public function pegar($slug) {
	    $permissao = null;
	    /** @var PermissaoInfo[] $permissoes */
	    $permissoes = $GLOBALS[PermissaoBLL::GLOBAL_PERMISSAO];
	    foreach ($permissoes as $perm) {
	        if ($perm->getSlug() == $slug) {
                $permissao = $perm;
                break;
            }
        }
		return $permissao;
	}

    /**
     * @param PermissaoInfo $permissao
     */
	public function registrar(PermissaoInfo $permissao) {
	    if (!array_key_exists(PermissaoBLL::GLOBAL_PERMISSAO, $GLOBALS)) {
            $GLOBALS[PermissaoBLL::GLOBAL_PERMISSAO] = array($permissao);
        }
        else {
            $GLOBALS[PermissaoBLL::GLOBAL_PERMISSAO][] = $permissao;
        }
    }
}

