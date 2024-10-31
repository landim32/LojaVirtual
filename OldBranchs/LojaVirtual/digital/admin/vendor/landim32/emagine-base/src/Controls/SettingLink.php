<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 31/07/2017
 * Time: 11:20
 */

namespace Emagine\Base\Controls;


class SettingLink
{
    private $slug;
    private $name;
    private $icon;
    private $url;
    private $description;

    /**
     * SettingLink constructor.
     * @param string $slug
     * @param string $name
     * @param string $url
     * @param string $icon
     * @param string $description
     */
    public function __construct($slug, $name, $url, $icon = "fa fa-reorder", $description = "")
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->icon = $icon;
        $this->url = $url;
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
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string $value
     */
    public function setUrl($value) {
        $this->url = $value;
    }
}