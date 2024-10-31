<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 11:17
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDResponse implements JsonSerializable
{
    private $status;
    private $waypoints = array();
    private $routes = array();

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $value
     */
    public function setStatus($value) {
        $this->status = $value;
    }

    /**
     * @return GDWaypointInfo[]
     */
    public function getWaypoints() {
        return $this->waypoints;
    }

    /**
     * @param GDWaypointInfo[] $value
     */
    public function setWaypoints($value) {
        $this->waypoints = $value;
    }

    public function clearWaypoint() {
        $this->waypoints = array();
    }

    /**
     * @param GDWaypointInfo $value
     */
    public function addWaypoint($value) {
        $this->waypoints[] = $value;
    }

    /**
     * @return GDRouteInfo[]
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @param GDRouteInfo[] $value
     */
    public function setRoutes($value) {
        $this->routes = $value;
    }

    public function clearRoutes() {
        $this->routes = array();
    }

    /**
     * @param GDRouteInfo $value
     */
    public function addRoute($value) {
        $this->routes[] = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize() {
        $json = new stdClass();
        $json->geocoded_waypoints = array();
        foreach ($this->getWaypoints() as $waypoint) {
            $json->geocoded_waypoints[] = $waypoint->jsonSerialize();
        }
        $json->routes = array();
        foreach ($this->getRoutes() as $route) {
            $json->routes[] = $route->jsonSerialize();
        }
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDResponse
     */
    public static function fromJSON($value) {
        $response = new GDResponse();
        $response->setStatus($value->status);
        $response->clearWaypoint();
        if (isset($value->geocoded_waypoints)) {
            foreach ($value->geocoded_waypoints as $waypoint) {
                $response->addWaypoint(GDWaypointInfo::fromJSON($waypoint));
            }
        }
        $response->clearRoutes();
        if (isset($value->routes)) {
            foreach ($value->routes as $route) {
                $response->addRoute(GDRouteInfo::fromJSON($route));
            }
        }
        return $response;
    }
}