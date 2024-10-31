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

class GDPositionInfo implements JsonSerializable
{
    private $lat;
    private $lng;

    /**
     * @return float
     */
    public function getLatitude() {
        return $this->lat;
    }

    /**
     * @param float $value
     */
    public function setLatitude($value) {
        $this->lat = $value;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->lng;
    }

    /**
     * @param float $value
     */
    public function setLongitude($value) {
        $this->lng = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $json = new stdClass();
        $json->latitude = $this->getLatitude();
        $json->longitude = $this->getLongitude();
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDPositionInfo
     */
    public static function fromJSON($value) {
        $wp = new GDPositionInfo();
        $wp->setLatitude($value->lat);
        $wp->setLongitude($value->lng);
        return $wp;
    }
}