<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 31/07/2017
 * Time: 11:08
 */

namespace Emagine\Base\Controls;

class SettingCategory
{
    private $slug;
    private $name;
    private $description;
    private $icon;
    private $links = array();

    /**
     * SettingCategory constructor.
     * @param string $slug
     * @param string $name
     * @param string $icon
     * @param string $description
     */
    public function __construct($slug, $name, $icon = "fa fa-reorder", $description = "")
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->icon = $icon;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param string $value
     */
    public function setSlug($value) {
        $this->slug = $value;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setName($value) {
        $this->name = $value;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $value
     */
    public function setDescription($value) {
        $this->description = $value;
    }

    /**
     * @return string
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param string $value
     */
    public function setIcon($value) {
        $this->icon = $value;
    }

    /**
     * @return SettingLink[]
     */
    public function getLinks() {
        return $this->links;
    }

    /**
     * @param string $slug
     * @return SettingLink
     */
    public function get($slug) {
        return $this->links[$slug];
    }

    /**
     * @param string $slug
     * @param SettingLink $link
     * @return SettingLink
     */
    public function set($slug, SettingLink $link) {
        $this->links[$slug] = $link;
        return $link;
    }

    /**
     * @param SettingLink $link
     * @return SettingLink
     */
    public function addLink(SettingLink $link) {
        $this->links[$link->getSlug()] = $link;
        return $link;
    }
}