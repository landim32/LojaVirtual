<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 12:12
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDBoundInfo implements JsonSerializable
{
    private $northeast;
    private $southwest;

    /**
     * @return GDPositionInfo
     */
    public function getNortheast()
    {
        return $this->northeast;
    }

    /**
     * @param GDPositionInfo $value
     */
    public function setNortheast($value)
    {
        $this->northeast = $value;
    }

    /**
     * @return GDPositionInfo
     */
    public function getSouthwest()
    {
        return $this->southwest;
    }

    /**
     * @param GDPositionInfo $value
     */
    public function setSouthwest($value)
    {
        $this->southwest = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $json = new stdClass();
        if (!is_null($this->getNortheast())) {
            $json->northeast = $this->getNortheast()->jsonSerialize();
        }
        if (!is_null($this->getSouthwest())) {
            $json->southwest = $this->getSouthwest()->jsonSerialize();
        }
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDBoundInfo
     */
    public static function fromJSON($value)
    {
        $bound = new GDBoundInfo();
        if (isset($value->northeast)) {
            $bound->setNortheast($value->northeast);
        }
        if (isset($value->southwest)) {
            $bound->setSouthwest($value->southwest);
        }
        return $bound;
    }
}