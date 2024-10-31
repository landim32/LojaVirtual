<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 02/12/17
 * Time: 00:08
 */

namespace Landim32\EasyDB;


class DBMigrateItem
{
    private $version = 0;
    private $tableName = "";
    private $callable = null;

    /**
     * DBMigrateItem constructor.
     * @param int $version
     * @param string $tableName
     * @param callable $callable
     */
    public function __construct($version, $tableName, callable $callable)
    {
        $this->version = $version;
        $this->tableName = $tableName;
        $this->callable = $callable;
    }

    /**
     * @return int
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * @param int $value
     */
    public function setVersion($value) {
        $this->version = $value;
    }

    /**
     * @return string
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * @param string $value
     */
    public function setTableName($value) {
        $this->tableName = $value;
    }

    /**
     * @return callable|null
     */
    public function getCallable() {
        return $this->callable;
    }

    /**
     * @param callable|null $value
     */
    public function setCallable($value) {
        $this->callable = $value;
    }
}