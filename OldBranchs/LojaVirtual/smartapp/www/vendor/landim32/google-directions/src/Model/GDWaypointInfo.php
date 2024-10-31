<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 11:23
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDWaypointInfo implements JsonSerializable
{
    private $geocoder_status;
    private $place_id;
    private $types;

    /**
     * @return string
     */
    public function getStatus() {
        return $this->geocoder_status;
    }

    /**
     * @param string $value
     */
    public function setStatus($value) {
        $this->geocoder_status = $value;
    }

    /**
     * @return string
     */
    public function getPlaceId() {
        return $this->place_id;
    }

    /**
     * @param string $value
     */
    public function setPlaceId($value) {
        $this->place_id = $value;
    }

    /**
     * @return string[]
     */
    public function getTypes() {
        return $this->types;
    }

    /**
     * @param string[] $value
     */
    public function setTypes($value) {
        $this->types = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $json = new stdClass();
        $json->geocoder_status = $this->getStatus();
        $json->place_id = $this->getPlaceId();
        $json->types = $this->getTypes();
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDWaypointInfo
     */
    public static function fromJSON($value) {
        $wp = new GDWaypointInfo();
        $wp->setStatus($value->geocoder_status);
        $wp->setPlaceId($value->place_id);
        $wp->setTypes($value->types);
        return $wp;
    }
}