<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 07/07/2017
 * Time: 19:26
 */

namespace Landim32\BtMenu;

class BtMenuSeparator extends BtMenuBase
{
    /**
     * @param string $format
     * @return string
     */
    public function render($format = "navbar") {
        $str = "";
        if ($format == BtMenuBase::NAVBAR) {
            $str = "<li role=\"separator\" class=\"divider\"></li>\n";
        }
        return $str;
    }
}