<?php
namespace Emagine\Produto\Report;


class ReportField
{
    const STRING = "string";
    const INT = "int";
    const DOUBLE = "double";
    const DATE = "date";

    private $fieldname;
    private $name;
    private $fieldtype = "string";
    private $from;
    private $where;
    private $orderAsc;
    private $orderDesc;
    private $url = "";
    private $urlFieldName = "";
    private $value;
    private $visible = true;
    private $filter = false;
    private $onShow = null;

    /**
     * @return string
     */
    public function getFieldName() {
        return $this->fieldname;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setFieldName($value) {
        $this->fieldname = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setName($value) {
        $this->name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldType() {
        return $this->fieldtype;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setFieldType($value) {
        $this->fieldtype = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setFrom($value) {
        $this->from = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getWhere() {
        return $this->where;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setWhere($value) {
        $this->where = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderAsc() {
        return $this->orderAsc;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setOrderAsc($value) {
        $this->orderAsc = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDesc() {
        return $this->orderDesc;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setOrderDesc($value) {
        $this->orderDesc = $value;
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
     * @return ReportField
     */
    public function setUrl($value) {
        $this->url = $value;
        return $this;
    }

    /**
     * @return string|array
     */
    public function getUrlFieldName() {
        return $this->urlFieldName;
    }

    /**
     * @param string|array $value
     * @return ReportField
     */
    public function setUrlFieldName($value) {
        $this->urlFieldName = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param string $value
     * @return ReportField
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getVisible() {
        return $this->visible;
    }

    /**
     * @param bool $value
     * @return ReportField
     */
    public function setVisible($value) {
        $this->visible = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getFilter() {
        return $this->filter;
    }

    /**
     * @param bool $value
     * @return ReportField
     */
    public function setFilter($value) {
        $this->filter = $value;
        return $this;
    }

    /**
     * @return callable
     */
    public function getOnShow() {
        return $this->onShow;
    }

    /**
     * @param callable $onShow
     * @return $this
     */
    public function setOnShow(callable $onShow) {
        $this->onShow = $onShow;
        return $this;
    }
}