<?php
/**
 * Created by PhpStorm.
 * User: foursys
 * Date: 30/12/17
 * Time: 14:28
 */

namespace Landim32\GoogleDirectionApi\Model;

use stdClass;
use JsonSerializable;

class GDValueInfo implements JsonSerializable
{
    private $value;
    private $text;

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $value
     */
    public function setText($value) {
        $this->text = $value;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize()
    {
        $json = new stdClass();
        $json->value = $this->getValue();
        $json->text = $this->getText();
        return $json;
    }

    /**
     * @param stdClass $value
     * @return GDValueInfo
     */
    public static function fromJSON($value)
    {
        $retorno = new GDValueInfo();
        $retorno->setValue($value->value);
        $retorno->setText($value->text);
        return $retorno;
    }
}