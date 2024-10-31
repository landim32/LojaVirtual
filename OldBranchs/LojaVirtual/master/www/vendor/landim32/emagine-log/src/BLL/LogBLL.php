<?php
namespace Emagine\Log\BLL;

use Emagine\Log\Model\LogFiltroInfo;
use Emagine\Log\Model\LogRetornoInfo;
use Exception;
use Landim32\EasyDB\DB;
use Emagine\Log\DAL\LogDAL;
use Emagine\Log\Model\LogInfo;

class LogBLL {

    /**
     * @throws Exception
     * @param LogFiltroInfo $filtro
     * @return LogRetornoInfo
     */
    public function listar(LogFiltroInfo $filtro) {
		$dal = new LogDAL();
		return $dal->listar($filtro);
	}

    /**
     * @return string[]
     */
	public function listarTipo() {
	    return array(
	        LogInfo::ERRO => "Erro",
            LogInfo::AVISO => "Aviso",
            LogInfo::INFORMACAO => "Informação"
        );
    }

	/**
     * @throws Exception
	 * @param int $id_log
	 * @return LogInfo
	 */
	public function pegar($id_log) {
		$dal = new LogDAL();
		return $dal->pegar($id_log);
	}

	/**
	 * @throws Exception
	 * @param LogInfo $log
	 */
	protected function validar(&$log) {
		if (isNullOrEmpty($log->getTitulo())) {
			throw new Exception('Título não informado.');
		}
        $descricao = $log->getDescricao();
		if (strlen($descricao) > 65535) {
		    $descricao = substr($descricao, 0, 65535);
		    $log->setDescricao($descricao);
        }
	}

	/**
	 * @throws Exception
	 * @param LogInfo $log
	 * @param bool $transaction
	 * @return int
	 */
	public function inserir($log, $transaction = true) {
		$this->validar($log);
		$dal = new LogDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
            $id_log = $dal->inserir($log);
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
		return $id_log;
	}

	/**
	 * @throws Exception
	 * @param int $id_log
	 * @param bool $transaction
	 */
	public function excluir($id_log, $transaction = true) {
		$dal = new LogDAL();
		try{
			if ($transaction === true) {
				DB::beginTransaction();
			}
			$dal->excluir($id_log);
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

