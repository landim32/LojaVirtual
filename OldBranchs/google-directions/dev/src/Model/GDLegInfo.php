<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 14:25
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDLegInfo implements JsonSerializable
{
    private $distance;
    private $duration;
    private $end_address;
    private $end_location;
    private $start_address;
    private $start_location;
    private $steps;

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
     * @return string
     */
    public function getEndAddress() {
        return $this->end_address;
    }

    /**
     * @param string $value
     */
    public function setEndAddress($value) {
        $this->end_address = $value;
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
    public function getStartAddress() {
        return $this->start_address;
    }

    /**
     * @param string $value
     */
    public function setStartAddress($value) {
        $this->start_address = $value;
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
     * @return GDStepInfo[]
     */
    public function getSteps() {
        return $this->steps;
    }

    /**
     * @param GDStepInfo[] $value
     */
    public function setSteps($value) {
        $this->steps = $value;
    }

    public function clearSteps() {
        $this->steps = array();
    }

    /**
     * @param GDStepInfo $step
     */
    public function addStep($step) {
        $this->steps[] = $step;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $json = new stdClass();
        if (!is_null($this->getDistance())) {
            $json->distance = $this->getDistance()->jsonSerialize();
        }
        if (!is_null($this->getDuration())) {
            $json->distance = $this->getDuration()->jsonSerialize();
        }
        $json->end_address = $this->getEndAddress();
        if (!is_null($this->getEndLocation())) {
            $json->end_position = $this->getEndLocation()->jsonSerialize();
        }
        $json->end_address = $this->getStartAddress();
        if (!is_null($this->getStartLocation())) {
            $json->end_position = $this->getStartLocation()->jsonSerialize();
        }
        $json->steps = array();
        foreach ($this->getSteps() as $step) {
            $json->steps[] = $step->jsonSerialize();
        }
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDLegInfo
     */
    public static function fromJSON($value)
    {
        $retorno = new GDLegInfo();
        if (isset($value->distance)) {
            $retorno->setDistance(GDValueInfo::fromJSON($value->distance));
        }
        if (isset($value->duration)) {
            $retorno->setDuration(GDValueInfo::fromJSON($value->duration));
        }
        if (isset($value->start_address)) {
            $retorno->setStartAddress($value->start_address);
        }
        if (isset($value->start_location)) {
            $retorno->setStartLocation(GDPositionInfo::fromJSON($value->start_location));
        }
        if (isset($value->end_address)) {
            $retorno->setEndAddress($value->end_address);
        }
        if (isset($value->end_location)) {
            $retorno->setEndLocation(GDPositionInfo::fromJSON($value->end_location));
        }
        $retorno->clearSteps();
        if (isset($value->steps)) {
            foreach ($value->steps as $step) {
                $retorno->addStep(GDStepInfo::fromJSON($step));
            }
        }
        return $retorno;

    }
}