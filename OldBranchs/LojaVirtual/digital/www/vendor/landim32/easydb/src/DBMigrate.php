<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 01/12/17
 * Time: 17:02
 */

namespace Landim32\EasyDB;

use PDO;

class DBMigrate
{
    const VERSION_TABLENAME = "db_version";

    private $version_start = false;
    private $itens = array();
    private $tables = array();

    /**
     * @throws DBException
     */
    private function createVersionTable() {
        $query  = "CREATE TABLE " . DBMigrate::VERSION_TABLENAME . " (";
        $query .= "tablename VARCHAR(255) NOT NULL,";
        $query .= "version INT NOT NULL,";
        $query .= "PRIMARY KEY (tablename))";
        DB::exec($query);
    }

    /**
     * @throws DBException
     */
    public function initVersion() {
        if (!$this->version_start && !DB::tableExist(DBMigrate::VERSION_TABLENAME)) {
            $this->createVersionTable();
            $this->version_start = true;
        }
    }

    /**
     * @param string $tableName
     * @throws DBException
     * @return int
     */
    private function getInternalTableVersion($tableName) {
        $query  = "
            SELECT version 
            FROM " . DBMigrate::VERSION_TABLENAME . "
            WHERE tablename = :table_name
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":table_name", $tableName);
        $db->execute();
        return DB::getValue($db, "version");
    }


    /**
     * @param string $tablename
     * @param int $version
     * @throws DBException
     */
    private function setInternalTableVersion($tablename, $version) {
        $query  = "
            UPDATE " . DBMigrate::VERSION_TABLENAME . " SET
                version = :version
            WHERE tablename = :tablename
        ";
        $db = DB::getDB()->prepare($query);
        $db->bindValue(":tablename", $tablename);
        $db->bindValue(":version", $version, PDO::PARAM_INT);
        $db->execute();
        if ($db->rowCount() == 0) {
            $query  = "
                INSERT INTO " . DBMigrate::VERSION_TABLENAME . " (
                    tablename,
                    version
                ) VALUES (
                    :tablename,
                    :version
                )
            ";
            $db = DB::getDB()->prepare($query);
            $db->bindValue(":table_name", $tablename);
            $db->bindValue(":version", $version, PDO::PARAM_INT);
            $db->execute();
        }
    }

    /**
     * @param string $tablename
     * @return int
     * @throws DBException
     */
    public function getTableVersion($tablename) {
        if (!array_key_exists($tablename, $this->tables)) {
            $version = $this->getInternalTableVersion($tablename);
            $this->tables[$tablename] = $version;
        }
        return $this->tables[$tablename];
    }

    /**
     * @param string $tablename
     * @param int $version
     * @throws DBException
     */
    public function setTableVersion($tablename, $version) {
        $this->setInternalTableVersion($tablename, $version);
        $this->tables[$tablename] = $version;
    }

    /**
     * @param int $version
     * @param string $tablename
     * @param callable $callable
     */
    public function add($version, $tablename, callable $callable) {
        $this->itens[] = new DBMigrateItem($version, $tablename, $callable);
    }

    /**
     * @throws DBException
     * @return bool
     */
    public function execute() {
        $this->initVersion();
        $retorno = false;

        /** @var DBMigrateItem $item */
        foreach ($this->itens as $item) {
            $version = $this->getTableVersion($item->getTableName());
            if ($item->getVersion() > $version) {
                $callable = $item->getCallable();
                if (is_callable($callable)) {
                    if ($callable() === true) {
                        $this->setTableVersion($item->getTableName(), $item->getVersion());
                    }
                    else {
                        $retorno = false;
                        break;
                    }
                }
                else {
                    $retorno = false;
                    break;
                }
            }
        }
        return $retorno;
    }
}