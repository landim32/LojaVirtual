<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 03:17
 */

namespace Landim32\GoogleDirectionApi\BLL;

use Exception;
use GuzzleHttp\Client;
use Landim32\GoogleDirectionApi\Model\GDResponse;

class GoogleDirectionApi
{
    private $api_key;
    private $origin;
    private $waypoints = array();
    private $destination;

    const URL_MAP_API = "https://maps.googleapis.com/maps/api/directions/json";

    /**
     * GoogleDirectionApi constructor.
     * @param string $api_key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return string
     */
    public function getOrigin() {
        return $this->origin;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setOrigin($value) {
        $this->origin = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestination() {
        return $this->destination;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setDestination($value) {
        $this->destination = $value;
        return $this;
    }

    /**
     * Clear all Waypoints
     */
    public function clearWaypoints() {
        $this->waypoints = array();
    }

    /**
     * @return string[]
     */
    public function getWaypoints() {
        return $this->waypoints;
    }

    /**
     * @param string $value
     */
    public function addWaypoint($value) {
        $this->waypoints[] = $value;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateUrl() {
        $url = GoogleDirectionApi::URL_MAP_API;
        $params = array();
        $params["key"] = $this->api_key;
        $params["origin"] = $this->getOrigin();
        if (count($this->getWaypoints()) > 0) {
            $params["waypoints"] = "via:" . implode("|", $this->getWaypoints());
        }
        $params["destination"] = $this->getDestination();

        $args = array();
        foreach ($params as $key => $value) {
            $args[] = $key . "=" . urlencode($value);
        }
        return $url . "?" . implode("&", $args);
    }

    /**
     * @return GDResponse|null
     * @throws Exception
     */
    public function execute() {
        $client = new Client();
        $res = $client->request('GET', $this->generateUrl());
        if ($res->getStatusCode() == 200) {
            $json = json_decode($res->getBody()->getContents());
            //var_dump($json);
            return GDResponse::fromJSON($json);
        }
        return null;
    }
}