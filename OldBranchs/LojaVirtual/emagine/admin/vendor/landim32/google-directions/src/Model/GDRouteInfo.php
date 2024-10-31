<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 12:09
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDRouteInfo implements JsonSerializable
{
    private $bounds;
    private $copyrights;
    private $overview_polyline;
    private $summary;
    private $legs = array();

    /**
     * @return GDBoundInfo
     */
    public function getBounds() {
        return $this->bounds;
    }

    /**
     * @param GDBoundInfo $value
     */
    public function setBounds($value) {
        $this->bounds = $value;
    }

    /**
     * @return GDLegInfo[]
     */
    public function getLegs() {
        return $this->legs;
    }

    /**
     * @param GDLegInfo[] $value
     */
    public function setLegs($value) {
        $this->legs = $value;
    }

    public function clearLegs() {
        $this->legs = array();
    }

    /**
     * @param GDLegInfo $leg
     */
    public function addLeg($leg) {
        $this->legs[] = $leg;
    }

    /**
     * @return string
     */
    public function getCopyright() {
        return $this->copyrights;
    }

    /**
     * @param string $value
     */
    public function setCopyright($value) {
        $this->copyrights = $value;
    }

    /**
     * @return GDPolylineInfo
     */
    public function getOverviewPolyline() {
        return $this->overview_polyline;
    }

    /**
     * @param GDPolylineInfo $value
     */
    public function setOverviewPolyline($value) {
        $this->overview_polyline = $value;
    }

    /**
     * @return string
     */
    public function getSummary() {
        return $this->summary;
    }

    /**
     * @param string $value
     */
    public function setSummary($value) {
        $this->summary = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $json = new stdClass();
        $json->bounds = $this->getBounds()->jsonSerialize();
        $json->legs = array();
        foreach ($this->getLegs() as $leg) {
            $json->legs[] = $leg->jsonSerialize();
        }
        $json->copyrights = $this->getCopyright();
        if (!is_null($this->getOverviewPolyline())) {
            $json->overview_polyline = $this->getOverviewPolyline()->jsonSerialize();
        }
        $json->summary = $this->getSummary();
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDRouteInfo
     */
    public static function fromJSON($value) {
        $route = new GDRouteInfo();
        if (isset($value->bounds)) {
            $route->setBounds(GDBoundInfo::fromJSON($value->bounds));
        }
        $route->clearLegs();
        if (isset($value->legs)) {
            foreach ($value->legs as $leg) {
                $route->addLeg(GDLegInfo::fromJSON($leg));
            }
        }
        if (isset($value->copyrights)) {
            $route->setCopyright($value->copyrights);
        }
        if (isset($value->overview_polyline)) {
            $route->setOverviewPolyline(GDPolylineInfo::fromJSON($value->overview_polyline));
        }
        if (isset($value->summary)) {
            $route->setSummary($value->summary);
        }
        return $route;
    }
}