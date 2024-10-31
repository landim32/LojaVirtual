<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 21:20
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDStepInfo implements JsonSerializable
{
    private $distance;
    private $duration;
    private $start_location;
    private $end_location;
    private $html_instructions;
    private $polyline;
    private $travel_mode;
    private $maneuver;

    /**
     * @return GDValueInfo
     */
    public function getDistance() {
        return $this->distance;
    }

    /**
     * @param GDValueInfo $value
     */
    public function setDistance($value) {
        $this->distance = $value;
    }

    /**
     * @return GDValueInfo
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * @param GDValueInfo $value
     */
    public function setDuration($value) {
        $this->duration = $value;
    }

    /**
     * @return GDPositionInfo
     */
    public function getStartLocation() {
        return $this->start_location;
    }

    /**
     * @param GDPositionInfo $value
     */
    public function setStartLocation($value) {
        $this->start_location = $value;
    }

    /**
     * @return GDPositionInfo
     */
    public function getEndLocation() {
        return $this->end_location;
    }

    /**
     * @param GDPositionInfo $value
     */
    public function setEndLocation($value) {
        $this->end_location = $value;
    }

    /**
     * @return string
     */
    public function getHtmlInstruction() {
        return $this->html_instructions;
    }

    /**
     * @param string $value
     */
    public function setHtmlInstruction($value) {
        $this->html_instructions = $value;
    }

    /**
     * @return GDPolylineInfo
     */
    public function getPolyline() {
        return $this->polyline;
    }

    /**
     * @param GDPolylineInfo $value
     */
    public function setPolyline($value) {
        $this->polyline = $value;
    }

    /**
     * @return string
     */
    public function getTravelMode() {
        return $this->travel_mode;
    }

    /**
     * @param string $value
     */
    public function setTravelMode($value) {
        $this->travel_mode = $value;
    }

    /**
     * @return string
     */
    public function getManeuver() {
        return $this->maneuver;
    }

    /**
     * @param string $value
     */
    public function setManeuver($value) {
        $this->maneuver = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $json = new stdClass();
        if (!is_null($this->getDistance())) {
            $json->distance = $this->getDistance()->jsonSerialize();
        }
        if (!is_null($this->getDuration())) {
            $json->duration = $this->getDuration()->jsonSerialize();
        }
        if (!is_null($this->getStartLocation())) {
            $json->start_position = $this->getStartLocation()->jsonSerialize();
        }
        if (!is_null($this->getEndLocation())) {
            $json->end_position = $this->getEndLocation()->jsonSerialize();
        }
        $json->html_instructions = $this->getHtmlInstruction();
        if (!is_null($this->getPolyline())) {
            $json->polyline = $this->getPolyline()->jsonSerialize();
        }
        $json->travel_mode = $this->getTravelMode();
        $json->maneuver = $this->getManeuver();
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDStepInfo
     */
    public static function fromJSON($value) {
        $step = new GDStepInfo();
        if (isset($value->distance)) {
            $step->setDistance(GDValueInfo::fromJSON($value->distance));
        }
        if (isset($value->duration)) {
            $step->setDuration(GDValueInfo::fromJSON($value->duration));
        }
        if (isset($value->start_location)) {
            $step->setStartLocation($value->start_location);
        }
        if (isset($value->end_location)) {
            $step->setEndLocation($value->end_location);
        }
        $step->setHtmlInstruction($value->html_instructions);
        if (isset($value->polyline)) {
            $step->setPolyline($value->polyline);
        }
        $step->setTravelMode($value->travel_mode);
        if (isset($value->maneuver)) {
            $step->setManeuver($value->maneuver);
        }
        return $step;
    }
}