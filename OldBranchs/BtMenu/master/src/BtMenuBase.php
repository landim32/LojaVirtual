<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 07/07/2017
 * Time: 19:21
 */

namespace Landim32\BtMenu;

abstract class BtMenuBase
{
    const NAVBAR = "navbar";
    const LIST_GROUP = "list-group";

    /**
     * @param string $format
     * @return string
     */
    public abstract function render($format = "navbar");

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}