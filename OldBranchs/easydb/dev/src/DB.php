<?php

namespace Landim32\EasyDB;

use PDO;
use PDOStatement;

/**
 * Integracao com o Banco de dados usando PDO
 */
class DB {

    /**
     * @throws DBException
     * @return PDO
     */
    public static function getDB() {
        $cnn = $GLOBALS["_cnn"];
        if (is_null($cnn)) {
            if (!defined("DB_HOST")) {
                throw new DBException("DB_HOST não foi definido.");
            }
            if (!defined("DB_NAME")) {
                throw new DBException("DB_NAME não foi definido.");
            }
            if (!defined("DB_USER")) {
                throw new DBException("DB_NAME não foi definido.");
            }
            if (!defined("DB_PASS")) {
                throw new DBException("DB_PASS não foi definido.");
            }

            $cnnStr = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            $cnn = new PDO($cnnStr, DB_USER, DB_PASS);
            $cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $cnn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $GLOBALS["_cnn"] = $cnn;
        }
        return $cnn;
    }

    /**
     * @param PDOStatement $db
     * @param string $fieldName
     * @return array
     */
    public static function getList($db, $fieldName) {
        $retorno = array();
        while ($row = $db->fetch(PDO::FETCH_ASSOC)) {
            $retorno[] = $row[$fieldName];
        }
        $db->closeCursor();
        return $retorno;
    }

    /**
     * @param PDOStatement $db
     * @param string $key
     * @param string $fieldName
     * @return array
     */
    public static function getDictionary($db, $key, $fieldName) {
        $retorno = array();
        while ($row = $db->fetch(PDO::FETCH_ASSOC)) {
            $retorno[$row[$key]] = $row[$fieldName];
        }
        $db->closeCursor();
        return $retorno;
    }

    /**
     * @param PDOStatement $db
     * @param string $fieldName
     * @return null|mixed
     */
    public static function getValue($db, $fieldName) {
        $retorno = null;
        if ($row = $db->fetch(PDO::FETCH_ASSOC)) {
            $retorno = $row[$fieldName];
        }
        $db->closeCursor();
        return $retorno;
    }

    /**
     * @param PDOStatement $db
     * @param string $className
     * @return array
     */
    public static function getResult($db, $className = "stdClass") {
        $retorno = array();
        $db->execute();
        while ($row = $db->fetchObject($className)) {
            $retorno[] = $row;
        }
        $db->closeCursor();
        return $retorno;
    }

    /**
     * Pegar dado do banco e jogar parar classe
     * @param mixed $db
     * @param string $className
     * @throws DBException
     * @return mixed
     */
    public static function getValueAsClass($db, $className = "") {
        if (trim($className) == "")
            throw new DBException("Preencha o nome da classe.");
        $db->execute();
        $retorno = null;
        if ($row = $db->fetchObject($className)) {
            $retorno = $row;
        }
        $db->closeCursor();
        return $retorno;
    }

    /**
     * Pegar dado do banco e jogar parar classe
     * @param mixed $db
     * @param string $className
     * @throws DBException
     * @return mixed
     */
    public static function getValueClass($db, $className = "") {
        return DB::getValueAsClass($db, $className);
    }

    /**
     * Retorna o ultimo ID inserido
     * @throws DBException
     * @return int
     */
    public static function lastInsertId() {
        $query  = "SELECT LAST_INSERT_ID() AS 'lastid'";
        $db = DB::getDB()->prepare($query);
        $db->execute();
        $retorno = -1;
        if ($row = $db->fetch(PDO::FETCH_ASSOC)) {
            $retorno = intval($row["lastid"]);
        }
        $db->closeCursor();
        return $retorno;
    }

    /**
     * @param string $tableName
     * @return bool
     * @throws DBException
     */
    public static function tableExist($tableName) {
        $query  = "SHOW TABLES LIKE :table_name";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":table_name", $tableName);
        $db->execute();
        return ($db->rowCount() == 1);
    }

    /**
     * @param string $query
     * @throws DBException
     */
    public static function exec($query) {
        $db = DB::getDB()->prepare($query);
        $db->execute();
    }
    /**
     * Inicia transação SQL
     * @throws DBException
     * @return bool
     */
    public static function beginTransaction() {
        $db = DB::getDB();
        return $db->beginTransaction();
    }

    /**
     * Inicia transação SQL
     * @throws DBException
     * @return bool
     */
    public static function beginTrans() {
        return DB::beginTransaction();
    }

    /**
     * Executa do Commit no SQL
     * @throws DBException
     * @return bool
     */
    public static function commit() {
        $db = DB::getDB();
        return $db->commit();
    }

    /**
     * Volta atras nas alterações ao executar o beginTrans
     * @throws DBException
     * @return bool
     */
    public static function rollBack() {
        $db = DB::getDB();
        return $db->rollBack();
    }

    /**
     * Está em transação
     * @throws DBException
     * @return bool
     */
    public static function inTransaction() {
        $db = DB::getDB();
        return $db->inTransaction();
    }
}