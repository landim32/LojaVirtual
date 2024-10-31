<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 21:55
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDPolylineInfo implements JsonSerializable
{
    private $points;

    /**
     * @return string
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * @param string $value
     */
    public function setPoints($value) {
        $this->points = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $json = new stdClass();
        $json->points = $this->getPoints();
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDPolylineInfo
     */
    public static function fromJSON($value) {
        $polyline = new GDPolylineInfo();
        $polyline->setPoints($value->points);
        return $polyline;
    }
}