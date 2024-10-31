<?php
namespace Emagine\Log\DAL;

use Emagine\Log\Model\LogFiltroInfo;
use Emagine\Log\Model\LogInfo;
use Emagine\Log\Model\LogRetornoInfo;
use PDO;
use PDOStatement;
use Exception;
use Landim32\EasyDB\DB;

class LogDAL {

	/**
	 * @return string
	 */
	public function query() {
		return "
			SELECT SQL_CALC_FOUND_ROWS
                log.id_log,
                log.id_usuario,
                log.id_loja,
                log.cod_tipo,
                log.data_inclusao,
                log.titulo,
                log.descricao,
                usuario.nome AS 'nome'
			FROM log
			LEFT JOIN usuario ON usuario.id_usuario = log.id_usuario
		";
	}

	/**
     * @throws Exception
     * @param LogFiltroInfo $filtro
	 * @return LogRetornoInfo
	 */
	public function listar(LogFiltroInfo $filtro) {
		$query = $this->query() . "
			WHERE (1=1)
		";
		if (!is_null($filtro->getCodTipo())) {
		    $query .= " AND log.cod_tipo = :cod_tipo ";
        }
        if (!is_null($filtro->getIdUsuario())) {
            $query .= " AND log.id_usuario = :id_usuario ";
        }
        if (!is_null($filtro->getIdLoja())) {
            $query .= " AND log.id_loja = :id_loja ";
        }
        if (!is_null($filtro->getDataInicio()) && !is_null($filtro->getDataFim())) {
            $query .= " AND log.data_inclusao BETWEEN :data_inicio AND :data_fim ";
        }
        elseif (!is_null($filtro->getDataInicio())) {
            $query .= " AND log.data_inclusao >= :data_inicio ";
        }
        elseif (!is_null($filtro->getDataFim())) {
            $query .= " AND log.data_inclusao <= :data_fim ";
        }
        $query .= " ORDER BY log.data_inclusao DESC ";
        if ($filtro->getTamanhoPagina() > 0) {
            $pg = $filtro->getPagina() - 1;
            if ($pg < 0) $pg = 0;
            $pgini = ($pg * $filtro->getTamanhoPagina());
            $query .= " LIMIT " . $pgini . ", " . $filtro->getTamanhoPagina();
        }
		$db = DB::getDB()->prepare($query);
        if (!is_null($filtro->getCodTipo())) {
            $db->bindValue(":cod_tipo", $filtro->getCodTipo(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getIdUsuario())) {
            $db->bindValue(":id_usuario", $filtro->getIdUsuario(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getIdLoja())) {
            $db->bindValue(":id_loja", $filtro->getIdLoja(), PDO::PARAM_INT);
        }
        if (!is_null($filtro->getDataInicio()) && !is_null($filtro->getDataFim())) {
            $db->bindValue(":data_inicio", $filtro->getDataInicio());
            $db->bindValue(":data_fim", $filtro->getDataFim());
        }
        elseif (!is_null($filtro->getDataInicio())) {
            $db->bindValue(":data_inicio", $filtro->getDataInicio());
        }
        elseif (!is_null($filtro->getDataFim())) {
            $db->bindValue(":data_fim", $filtro->getDataFim());
        }
		$logs = DB::getResult($db,"Emagine\\Log\\Model\\LogInfo");
        if ($filtro->getTamanhoPagina() > 0) {
            $total = DB::getDB()->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        }
        else {
            $total = count($logs);
        }
        return (new LogRetornoInfo())
            ->setTamanhoPagina($filtro->getTamanhoPagina())
            ->setPagina($filtro->getPagina())
            ->setTotal($total)
            ->setLogs($logs);
	}

	/**
     * @throws Exception
	 * @param int $id_log
	 * @return LogInfo
	 */
	public function pegar($id_log) {
		$query = $this->query() . "
			WHERE log.id_log = :id_log
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_log", $id_log, PDO::PARAM_INT);
		return DB::getValueClass($db,"Emagine\\Log\\Model\\LogInfo");
	}

	/**
	 * @param PDOStatement $db
	 * @param LogInfo $log
	 */
	public function preencherCampo(PDOStatement $db, LogInfo $log) {

	    if ($log->getIdUsuario() > 0) {
            $db->bindValue(":id_usuario", $log->getIdUsuario(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_usuario", null);
        }
        if ($log->getIdLoja() > 0) {
            $db->bindValue(":id_loja", $log->getIdLoja(), PDO::PARAM_INT);
        }
        else {
            $db->bindValue(":id_loja", null);
        }
        $db->bindValue(":cod_tipo", $log->getCodTipo(), PDO::PARAM_INT);
        $db->bindValue(":nome", $log->getNome());
        $db->bindValue(":titulo", $log->getTitulo());
        $db->bindValue(":descricao", $log->getDescricao());
	}

	/**
     * @throws Exception
	 * @param LogInfo $log
	 * @return int
	 */
	public function inserir($log) {
		$query = "
			INSERT INTO log (
			    id_usuario,
                id_loja,
                cod_tipo,
                data_inclusao,
                nome,
                titulo,
                descricao
			) VALUES (
			    :id_usuario,
                :id_loja,
                :cod_tipo,
                NOW(),
                :nome,
                :titulo,
                :descricao
			)
		";
		$db = DB::getDB()->prepare($query);
		$this->preencherCampo($db, $log);
		$db->execute();
		return DB::lastInsertId();
	}

	/**
     * @throws Exception
	 * @param int $id_log
	 */
	public function excluir($id_log) {
		$query = "
			DELETE FROM log
			WHERE id_log = :id_log
		";
		$db = DB::getDB()->prepare($query);
		$db->bindValue(":id_log", $id_log, PDO::PARAM_INT);
		$db->execute();
	}
}

