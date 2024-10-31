<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 06/07/2017
 * Time: 18:03
 */

namespace Landim32\BtMenu;

class BtMainMenu
{
    private $className = null;
    private $format;
    private $menus = array();

    /**
     * BtMainMenu constructor.
     * @param string|null $className
     * @param string $format
     */
    public function __construct($format = "navbar", $className = null)
    {
        $this->className = $className;
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

    /**
     * @param string $value
     * @return BtMainMenu
     */
    public function setClassName($value) {
        $this->className = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param string $value
     */
    public function setFormat($value) {
        $this->format = $value;
    }

    public function cleanMenu() {
        $this->menus = array();
    }

    /**
     * @return BtMenu[]
     */
    public function getMenu() {
        return $this->menus;
    }

    /**
     * @param BtMenu $menu
     * @return BtMenu
     */
    public function addMenu(BtMenu $menu) {
        $this->menus[] = $menu;
        return $menu;
    }

    /**
     * Insere um menu no início
     * @param BtMenu $menu
     * @return BtMenu
     */
    public function insertMenu(BtMenu $menu) {
        array_unshift($this->menus, $menu);
        return $menu;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name) {
        $retorno = false;
        foreach ($this->getMenu() as $menu) {
            if ($menu->getLabel() == $name) {
                $retorno = true;
                break;
            }
        }
        return $retorno;
    }

    /**
     * Get menu by name, not search in submenus
     * @param string $name
     * @return BtMenu|null
     */
    public function get($name) {
        $retorno = null;
        foreach ($this->getMenu() as $menu) {
            if ($menu->getLabel() == $name) {
                $retorno = $menu;
                break;
            }
        }
        return $retorno;
    }

    /**
     * Get menu by name, not search in submenus
     * @param string $name
     * @return BtMenu|null
     */
    public function getByName($name) {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @return BtMenu|null
     */
    public function findByName($name) {
        $retorno = null;
        foreach ($this->getMenu() as $menu) {
            if ($menu->getLabel() == $name) {
                $retorno = $menu;
                break;
            }
            elseif ($menu instanceof BtMenu && $menu->getLabel() == $name) {
                $retorno = $menu;
                break;
            }
        }
        return $retorno;
    }

    /**
     * @return string
     */
    public function render() {
        $str = "";
        switch ($this->getFormat()) {
            case BtMenu::LIST_GROUP:
                $className = "list-group";
                if (strlen($this->getClassName()) > 0) {
                    $className .= " " . $this->getClassName();
                }
                $str .= sprintf("<div class=\"%s\">\n", $className);
                foreach ($this->getMenu() as $menu) {
                    $str .= $menu->render(BtMenu::LIST_GROUP);
                }
                $str .= "</div>\n";
                break;
            default:
                $className = "nav navbar-nav";
                if (strlen($this->getClassName()) > 0) {
                    $className .= " " . $this->getClassName();
                }
                $str .= sprintf("<ul class=\"%s\">\n", $className);
                foreach ($this->getMenu() as $menu) {
                    $str .= $menu->render();
                }
                $str .= "</ul>\n";
                break;
        }

        return $str;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}