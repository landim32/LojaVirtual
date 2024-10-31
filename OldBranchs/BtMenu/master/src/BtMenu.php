<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 06/07/2017
 * Time: 17:26
 */

namespace Landim32\BtMenu;


class BtMenu extends BtMenuBase
{
    private $label;
    private $icon;
    private $className;
    private $url;
    private $target;
    private $modal = false;
    private $badge = null;
    private $active = false;
    private $subMenu = array();

    /**
     * BtMenu constructor.
     * @param string $label
     * @param string $url
     * @param string $icon
     * @param string $className
     */
    public function __construct($label, $url, $icon = "", $className = "")
    {
        $this->label = $label;
        $this->className = $className;
        $this->icon = $icon;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @param string $value
     * @return BtMenu
     */
    public function setLabel($value) {
        $this->label = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param string $value
     * @return BtMenu
     */
    public function setIcon($value) {
        $this->icon = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * @param string $value
     * @return BtMenu
     */
    public function setClassName($value) {
        $this->className = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     * @return BtMenu
     */
    public function setUrl($value) {
        $this->url = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @param string $value
     * @return BtMenu
     */
    public function setTarget($value) {
        $this->target = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getModal() {
        return $this->modal;
    }

    /**
     * @param string $value
     * @return BtMenu
     */
    public function setModal($value) {
        $this->modal = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getBadge() {
        return $this->badge;
    }

    /**
     * @param bool $value
     * @return BtMenu
     */
    public function setBadge($value) {
        $this->badge = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return BtMenu
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return BtMenuBase[]
     */
    public function getSubMenu() {
        return $this->subMenu;
    }

    public function cleanSubMenu() {
        $this->subMenu = array();
    }

    /**
     * @param BtMenuBase $menu
     * @return BtMenuBase
     */
    public function addSubMenu(BtMenuBase $menu) {
        $this->subMenu[] = $menu;
        return $menu;
    }

    /**
     * @param BtMenuBase $menu
     * @return BtMenuBase
     */
    public function insertSubMenu(BtMenuBase $menu) {
        array_unshift($this->subMenu, $menu);
        return $menu;
    }

    /**
     * @param string $format
     * @return string
     */
    public function render($format = "navbar") {
        $str = "";
        switch ($format) {
            case BtMenu::LIST_GROUP:
                $className = "list-group-item";
                if (strlen($this->getClassName()) > 0) {
                    $className .= " " . $this->getClassName();
                }
                $str .= "<a href=\"" . $this->getUrl() . "\" class=\"" . $className . "\"";
                if ($this->getModal() == true) {
                    $str .= " data-toggle=\"modal\" data-target=\"" . $this->getUrl() . "\"";
                }
                if (strlen($this->getTarget()) > 0) {
                    $str .= " target=\"" . $this->getTarget() . "\"";
                }
                $str .= ">";
                if (strlen($this->getBadge()) > 0) {
                    $str .= sprintf("<span class=\"badge pull-right\">%s</span> ", $this->getBadge());
                }
                if (strlen($this->getIcon()) > 0) {
                    $str .= sprintf("<i class=\"%s\"></i> ", $this->getIcon());
                }
                $str .= $this->getLabel() . "</a>\n";
                break;
            default:
                if (count($this->subMenu) > 0) {
                    $classes = array("dropdown");
                    if ($this->isActive()) {
                        $classes[] = "active";
                    }
                    else {
                        $active = false;
                        foreach ($this->getSubMenu() as $menu) {
                            if ($menu instanceof BtMenu && $menu->isActive()) {
                                $active = true;
                                break;
                            }
                        }
                        if ($active) {
                            $classes[] = "active";
                        }
                    }
                    $str .= "<li class=\"" . implode(" ", $classes) . "\">";
                    $str .= "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" " .
                        "aria-haspopup=\"true\" aria-expanded=\"false\">";
                    if (strlen($this->getIcon()) > 0) {
                        $str .= sprintf("<i class=\"%s\"></i>", $this->getIcon());
                    }
                    if (strlen($this->getLabel()) > 0) {
                        $str .= " " . $this->getLabel();
                    }
                    $str .= "<span class=\"caret\"></span>";
                    $str .= "</a>\n";
                    $str .= "<ul class=\"dropdown-menu\">\n";
                    foreach ($this->getSubMenu() as $menu) {
                        $str .= $menu->render();
                    }
                    $str .= "</ul></li>\n";
                }
                else {
                    $classes = array($this->getClassName());
                    if ($this->isActive()) {
                        $classes[] = "active";
                    }
                    $str .= sprintf("<li class=\"%s\">", implode(" ", $classes));
                    $str .= "<a href=\"" . $this->getUrl() . "\"";
                    if ($this->getModal() == true) {
                        $str .= " data-toggle=\"modal\" data-target=\"" . $this->getUrl() . "\"";
                    }
                    if (strlen($this->getTarget()) > 0) {
                        $str .= " target=\"" . $this->getTarget() . "\"";
                    }
                    $str .= ">";
                    if (strlen($this->getIcon()) > 0) {
                        $str .= sprintf("<i class=\"%s\"></i>", $this->getIcon());
                        if (strlen($this->getLabel()) > 0) {
                            $str .= " " . $this->getLabel();
                        }
                    }
                    else {
                        $str .= $this->getLabel();
                    }
                    if (strlen($this->getBadge()) > 0) {
                        $str .= sprintf("<span class=\"badge pull-right\">%s</span> ", $this->getBadge());
                    }
                    $str .= "</a></li>\n";
                }
                break;
        }
        return $str;
    }
}