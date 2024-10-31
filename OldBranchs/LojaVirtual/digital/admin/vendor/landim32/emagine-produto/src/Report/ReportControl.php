<?php
namespace Emagine\Produto\Report;

use Exception;
use Landim32\EasyDB\DB;
use PDO;
use stdClass;

class ReportControl
{
    const ASC = "asc";
    const DESC = "desc";

    private $title = "";
    private $query = "";
    private $querySum = "";
    private $where = "";
    private $orderBy = "";
    private $groupBy = "";
    private $groupFieldName = null;
    private $currentUrl = "";
    private $pageCount = 10;
    private $queryParam = array();
    private $fields = array();

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setTitle($value) {
        $this->title = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setQuery($value) {
        $this->query = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuerySum() {
        return $this->querySum;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setQuerySum($value) {
        $this->querySum = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getWhere() {
        return $this->where;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setWhere($value) {
        $this->where = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderBy() {
        return $this->orderBy;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setOrderBy($value) {
        $this->orderBy = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupBy() {
        return $this->groupBy;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setGroupBy($value) {
        $this->groupBy = $value;
        return $this;
    }


    /**
     * @return string
     */
    public function getGroupFieldName() {
        return $this->groupFieldName;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setGroupFieldName($value) {
        $this->groupFieldName = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentUrl() {
        return $this->currentUrl;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setCurrentUrl($value) {
        $this->currentUrl = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageCount() {
        return $this->pageCount;
    }

    /**
     * @param $value
     * @return ReportControl
     */
    public function setPageCount($value) {
        $this->pageCount = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParam() {
        return $this->queryParam;
    }

    /**
     * @param array $value
     * @return ReportControl
     */
    public function setQueryParam($value) {
        $this->queryParam = $value;
        return $this;
    }

    /**
     * @return ReportField[]
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param $fieldName
     * @return ReportField
     */
    public function getField($fieldName) {
        return $this->fields[$fieldName];
    }

    /**
     * @param ReportField $field
     * @return ReportControl
     */
    public function addField(ReportField $field) {
        $this->fields[$field->getFieldName()] = $field;
        return $this;
    }

    /**
     * @param string $fieldname
     * @return ReportControl
     */
    public function removeField($fieldname) {
        unset($this->fields[$fieldname]);
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage() {
        $pg = 0;
        $queryParam = $this->getQueryParam();
        if (array_key_exists("pg", $queryParam)) {
            $pg = intval($queryParam["pg"]) - 1;
        }
        if ($pg < 0) {
            $pg = 0;
        }
        return $pg;
    }

    /**
     * @return string
     */
    protected function doWhere() {
        $query = "";
        $queryParam = $this->getQueryParam();
        if (count($this->getFields()) > 0) {
            $where = array();
            foreach ($this->getFields() as $field) {
                if (!isNullOrEmpty($field->getValue())) {
                    $query .= " " . $field->getFrom() . " \n";
                    $where[] = $field->getWhere();
                }
                elseif ($field->getFilter() == true) {
                    if ($field->getFieldType() == ReportField::DATE) {
                        $ini = $field->getFieldName() . "_ini";
                        $fim = $field->getFieldName() . "_fim";
                        if (array_key_exists($ini, $queryParam) && array_key_exists($fim, $queryParam)) {
                            if (strlen($queryParam[$ini]) == 10 && strlen($queryParam[$fim]) == 10) {
                                $query .= " " . $field->getFrom() . " \n";
                                $where[] = $field->getWhere();
                            }
                        }
                    }
                    else {
                        if (array_key_exists($field->getFieldName(), $queryParam)) {
                            $query .= " " . $field->getFrom() . " \n";
                            $where[] = $field->getWhere();
                        }
                    }
                }
            }
            if (!isNullOrEmpty($this->getWhere())) {
                $where[] = $this->getWhere();
            }
            if (count($where) > 0) {
                $query .= " WHERE " . implode(" AND ", $where) . "\n";
            }
        }
        return $query;
    }

    /**
     * @return string
     */
    protected function doOrderBy() {
        $orderBy = "";
        $queryParam = $this->getQueryParam();
        if (array_key_exists("o", $queryParam)) {
            $field = $this->getField($queryParam["o"]);
            if (!is_null($field)) {
                $sentido = ReportControl::ASC;
                if (array_key_exists("a", $queryParam) && $queryParam["a"] == ReportControl::DESC) {
                    $sentido = ReportControl::DESC;
                }
                if ($sentido == ReportControl::DESC) {
                    if (!isNullOrEmpty($field->getOrderDesc())) {
                        $orderBy = $field->getOrderDesc();
                    }
                }
                else {
                    if (!isNullOrEmpty($field->getOrderAsc())) {
                        $orderBy = $field->getOrderAsc();
                    }
                }
            }
        }
        if (isNullOrEmpty($orderBy)) {
            $orderBy = $this->getOrderBy() . "\n";
        }
        return $orderBy;
    }

    /**
     * @param string $query
     * @return array
     * @throws Exception
     */
    protected function doQuery($query) {
        $db = DB::getDB()->prepare($query);
        if (count($this->getFields()) > 0) {
            $queryParam = $this->getQueryParam();
            foreach ($this->getFields() as $field) {
                if ($field->getFieldType() == ReportField::INT) {
                    if ($field->getFilter() == true) {

                    }
                    elseif (!isNullOrEmpty($field->getValue())) {
                        $db->bindValue(":" . $field->getFieldName(), $field->getValue(), PDO::PARAM_INT);
                    }
                }
                elseif ($field->getFieldType() == ReportField::DATE) {
                    if ($field->getFilter() == true) {
                        $fieldIni = $field->getFieldName() . "_ini";
                        $fieldFim = $field->getFieldName() . "_fim";
                        if (array_key_exists($fieldIni, $queryParam)) {
                            $dataArray = explode("/", $queryParam[$fieldIni]);
                            if (count($dataArray) == 3) {
                                $dataIni = $dataArray[2] . "-" . $dataArray[1] . "-" . $dataArray[0] . " 00:00:00";
                                $db->bindValue(":" . $fieldIni, $dataIni);
                            }
                        }
                        if (array_key_exists($fieldFim, $queryParam)) {
                            $dataArray = explode("/", $queryParam[$fieldFim]);
                            if (count($dataArray) == 3) {
                                $dataFim = $dataArray[2] . "-" . $dataArray[1] . "-" . $dataArray[0] . " 23:59:59";
                                $db->bindValue(":" . $fieldFim, $dataFim);
                            }
                        }
                    }
                }
                else {
                    if ($field->getFilter() == true) {
                        if (array_key_exists($field->getFieldName(), $queryParam)) {
                            //var_dump($field->getFieldName(), $queryParam);
                            //exit();
                            $value = "%" . $queryParam[$field->getFieldName()] . "%";
                            $db->bindValue(":" . $field->getFieldName(), $value);
                        }
                    }
                    elseif (!isNullOrEmpty($field->getValue())) {
                        $db->bindValue(":" . $field->getFieldName(), $field->getValue());
                    }
                }
            }
        }
        return DB::getArray($db);
    }

    /**
     * @throws Exception
     * @return ReportResult
     */
    protected function executeQuery() {
        $query = $this->getQuery() . "\n";
        $query .= $this->doWhere();
        if (!isNullOrEmpty($this->getGroupBy())) {
            $query .= $this->getGroupBy() . "\n";
        }
        $query .= $this->doOrderBy();
        if ($this->getPageCount() > 0) {
            //$pg = intval($queryParam['pg']) - 1;
            $pg = $this->getCurrentPage();
            $pgini = ($pg * $this->getPageCount());
            $query .= " LIMIT " . $pgini . ", " . $this->getPageCount();
        }

        //die($query);
        $rows = $this->doQuery($query);
        $total = 0;
        if ($this->getPageCount() > 0) {
            $total = DB::getDB()->query('SELECT FOUND_ROWS();')->fetch(PDO::FETCH_COLUMN);
        }
        return new ReportResult($rows, $total);
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function executeQuerySum() {
        $query = $this->getQuerySum() . "\n";
        $query .= $this->doWhere();
        return $this->doQuery($query);
    }

    /**
     * @return string
     */
    protected function getPageUrl() {
        $queryUrl = array();
        foreach ($this->getQueryParam() as $key => $value) {
            if ($key != "pg") {
                $queryUrl[] = $key . "=" . str_replace("%", "%%", urlencode($value));
            }
        }
        if (count($queryUrl) > 0) {
            return $this->getCurrentUrl() . "?" . implode("&", $queryUrl) . "&pg=%s";
        }
        else {
            return $this->getCurrentUrl() . "?pg=%s";
        }
    }

    /**
     * @param string $orderBy
     * @return string
     */
    protected function getUrlHeader($orderBy) {
        $queryUrl = array();
        $currentOrder = "";
        $currentSentido = ReportControl::ASC;
        foreach ($this->getQueryParam() as $key => $value) {
            if ($key == "o") {
                $currentOrder = $value;
            }
            elseif ($key == "a") {
                $currentSentido = $value;
            }
            else {
                $queryUrl[] = $key . "=" . urlencode($value);
            }
        }
        $queryUrl[] = "o=" . urlencode($orderBy);
        if ($currentOrder == $orderBy && $currentSentido == ReportControl::ASC) {
            $queryUrl[] = "a=" . ReportControl::DESC;
        }
        return $this->getCurrentUrl() . "?" . implode("&", $queryUrl);
    }

    /**
     * @param ReportField $field
     * @return string
     */
    protected function renderTH(ReportField $field) {
        $queryParam = $this->getQueryParam();
        $orderBy = $queryParam['o'];
        if (array_key_exists("a", $queryParam) && $queryParam['a'] == ReportControl::DESC) {
            $order = ReportControl::DESC;
        }
        else {
            $order = ReportControl::ASC;
        }
        $html = "";
        $align = null;
        if (in_array($field->getFieldType(), array(ReportField::INT, ReportField::DOUBLE, ReportField::DATE))) {
            $align = "text-right";
        }
        $html .= "<th";
        if (!is_null($align)) {
            $html .= " class=\"" . $align . "\"";
        }
        $html .= ">";
        $hasOrder = !isNullOrEmpty($field->getOrderAsc()) || !isNullOrEmpty($field->getOrderDesc());
        if ($hasOrder) {
            $html .= "<a href=\"" . $this->getUrlHeader($field->getFieldName()) . "\">";
        }
        $html .= $field->getName();
        if ($hasOrder) {
            if ($orderBy == $field->getFieldName()) {
                if ($order == ReportControl::ASC) {
                    $html .= " <i class='fa fa-arrow-down'></i>";
                } else {
                    $html .= " <i class='fa fa-arrow-up'></i>";
                }
            }
            $html .= "</a>";
        }
        $html .= "</th>\n";
        return $html;
    }

    /**
     * @param ReportField $field
     * @param array $row
     * @param string $value
     * @param int $colSpan
     * @param string $tag
     * @return string
     */
    protected function renderTD(ReportField $field, $row, $value, $colSpan = 1, $tag = "td") {
        $html = "";
        $align = null;
        if (in_array($field->getFieldType(), array(ReportField::INT, ReportField::DOUBLE, ReportField::DATE))) {
            $align = "text-right";
        }
        $html .= "<" . $tag;
        if (!is_null($align)) {
            $html .= " class=\"" . $align . "\"";
        }
        if ($colSpan > 1) {
            $html .= " colspan=\"" . $colSpan . "\"";
        }
        $html .= ">";
        $useLink = false;
        if (!isNullOrEmpty($field->getUrl())) {
            $urlFields = array();
            if (is_array($field->getUrlFieldName())) {
                $urlFields = array();
                foreach ($field->getUrlFieldName() as $urlField) {
                    if (array_key_exists($urlField, $row)) {
                        $urlFields[] = $row[$urlField];
                    }
                }
            }
            elseif (!isNullOrEmpty($field->getUrlFieldName())) {
                if (array_key_exists($field->getUrlFieldName(), $row)) {
                    $urlFields[] = $row[$field->getUrlFieldName()];
                }
            }
            if (count($urlFields) > 0) {
                //var_dump($field->getUrl(), $urlFields);
                $url = vsprintf($field->getUrl(), $urlFields);
                $html .= "<a href=\"" . $url . "\">";
                $useLink = true;
            }
        }
        if (!is_null($field->getOnShow()) && is_callable($field->getOnShow())) {
            $onShow = $field->getOnShow();
            $html .= $onShow($value);
        }
        elseif ($field->getFieldType() == ReportField::INT) {
            $html .= number_format($value, 0, ",", ".");
        }
        elseif ($field->getFieldType() == ReportField::DOUBLE) {
            $html .= number_format($value, 2, ",", ".");
        }
        elseif ($field->getFieldType() == ReportField::DATE) {
            $html .= date("d/m/Y H:i", strtotime($value));
        }
        else {
            $html .= $value;
        }
        if ($useLink == true) {
            $html .= "</a>\n";
        }
        $html .= "</" . $tag . ">\n";
        return $html;
    }

    /**
     * @throws Exception
     * @param array $rows
     * @return string
     */
    protected function renderTable($rows) {
        $html = "";
        $html .= "<table class=\"table table-striped table-hover table-responsive\">\n";
        $html .= "<thead><tr>";

        $colSpan = 0;
        $row = array_values($rows)[0];
        foreach ($row as $fieldname => $value) {
            $field = $this->getField($fieldname);
            //var_dump($field, $field->getVisible());
            if (is_null($field) || $field->getVisible() != true) {
                continue;
            }
            if ($this->getGroupFieldName() == $field->getFieldName()) {
                continue;
            }
            $colSpan++;
        }
        foreach ($row as $fieldname => $value) {
            $field = $this->getField($fieldname);
            if (is_null($field) || $field->getVisible() != true) {
                continue;
            }
            if ($this->getGroupFieldName() == $field->getFieldName()) {
                continue;
            }
            $html .= $this->renderTH($field);
        }
        $html .= "</th></thead><tbody>";
        $groupLastValue = "";
        foreach ($rows as $row) {
            $groupCurrentValue = $row[$this->getGroupFieldName()];
            if ($groupCurrentValue != $groupLastValue) {
                $field = $this->getField($this->getGroupFieldName());
                if (!(is_null($field) || $field->getVisible() != true)) {
                    $html .= "<tr>\n";
                    $html .= $this->renderTD($field, $row, $groupCurrentValue, $colSpan, "th");
                    $html .= "</tr>\n";
                }
                $groupLastValue = $groupCurrentValue;
            }
            $html .= "<tr>\n";
            foreach ($row as $fieldname => $value) {
                $field = $this->getField($fieldname);
                if (is_null($field) || $field->getVisible() != true) {
                    continue;
                }
                if ($this->getGroupFieldName() == $field->getFieldName()) {
                    continue;
                }
                $html .= $this->renderTD($field, $row, $value);
            }
            $html .= "</tr>\n";
        }
        $html .= "</tbody>";
        //var_dump($row);
        if (!isNullOrEmpty($this->getQuerySum())) {
            $row = array_values($this->executeQuerySum())[0];
            $html .= "<tfoot>\n";
            $cols = 0;
            $htmlTotal = "";
            foreach ($row as $fieldname => $value) {
                $field = $this->getField($fieldname);
                if (is_null($field) || $field->getVisible() != true) {
                    continue;
                }
                if ($this->getGroupFieldName() == $field->getFieldName()) {
                    continue;
                }
                $htmlTotal .= $this->renderTD($field, $row, $value, 1, "th");
                $cols++;
            }
            //var_dump($cols);
            $totalColSpan = $colSpan - $cols;
            $html .= "<tr>\n";
            $html .= "<th class=\"text-right\" colspan=\"" . $totalColSpan . "\">Total:</th>\n";
            $html .= $htmlTotal;
            $html .= "</tr>\n";
            $html .= "</tfoot>\n";
        }
        $html .= "</table>";
        return $html;
    }

    /**
     * @return string
     */
    protected function renderForm() {
        $html = "";
        $usaForm = false;
        $keywordField = null;
        $dateFilter = null;
        foreach ($this->getFields() as $field) {
            if ($field->getFilter() == true) {
                switch ($field->getFieldType()) {
                    case ReportField::DATE:
                        $dateFilter = $field;
                        $usaForm = true;
                        break;
                    case ReportField::STRING:
                        $keywordField = $field;
                        $usaForm = true;
                        break;
                }
            }
        }
        if ($usaForm) {
            $hiddenFields = array();
            foreach ($this->getQueryParam() as $key => $value) {
                $hiddenFields[$key] = $value;
            }
            $html .= "<form method=\"GET\" class=\"form-horizontal\">\n";
            $dateFieldStr = null;
            if (!is_null($dateFilter)) {
                unset($hiddenFields[$dateFilter->getFieldName() . "_ini"]);
                unset($hiddenFields[$dateFilter->getFieldName() . "_fim"]);
                $dateFieldStr = $this->renderFieldDate($dateFilter);
            }
            $keywordFieldStr = null;
            if (!is_null($keywordField)) {
                unset($hiddenFields[$keywordField->getFieldName()]);
                $keywordFieldStr = $this->renderFieldKeyword($keywordField, is_null($dateFieldStr));
            }

            if (!isNullOrEmpty($keywordFieldStr) && !isNullOrEmpty($dateFieldStr)) {
                $html .= "<div class=\"row\">\n";
                $html .= "<div class=\"col-md-6\">" . $keywordFieldStr . "</div>\n";
                $html .= "<div class=\"col-md-6\">" . $dateFieldStr . "</div>\n";
                $html .= "</div>\n";
            }
            elseif (!isNullOrEmpty($keywordFieldStr)) {
                $html .= "<div class=\"row\">\n";
                $html .= "<div class=\"col-md-6 col-md-offset-6\">" . $keywordFieldStr . "</div>\n";
                $html .= "</div>\n";
            }
            elseif (!isNullOrEmpty($dateFieldStr)) {
                $html .= "<div class=\"row\">\n";
                $html .= "<div class=\"col-md-6 col-md-offset-6\">" . $dateFieldStr . "</div>\n";
                $html .= "</div>\n";
            }

            foreach ($hiddenFields as $key => $value) {
                $html .= "<input type=\"hidden\" name=\"" . $key . "\" value=\"" . $value . "\" />";
            }
            $html .= "</form>\n";
        }
        return $html;
    }

    /**
     * @param ReportField $field
     * @param bool $showBtn
     * @return string
     */
    protected function renderFieldKeyword(ReportField $field, $showBtn = false) {
        $queryParam = $this->getQueryParam();
        $fieldName = $field->getFieldName();
        $value = "";
        if (array_key_exists($fieldName, $queryParam)) {
            $value = $queryParam[$fieldName];
        }
        $html = "";
        $html .= "<div class=\"input-group\">\n";
        if ($showBtn != true) {
            $html .= "<span class=\"input-group-addon\"><i class=\"fa fa-search\"></i></span>";
        }
        $html .= "<input type=\"text\" name=\"" . $fieldName . "\" class=\"form-control\" " .
            "placeholder=\"Busca por palavra-chave\" value=\"" . $value . "\" />";
        if ($showBtn == true) {
            $html .= "<span class=\"input-group-btn\">\n";
            $html .= "<button type=\"submit\" class=\"btn btn-primary\"><i class=\"fa fa-search\"></i></button>\n";
            $html .= "</span>\n";
        }
        $html .= "</div>";
        return $html;
    }

    /**
     * @param ReportField $field
     * @return string
     */
    protected function renderFieldDate(ReportField $field) {
        $queryParam = $this->getQueryParam();
        $fieldName = $field->getFieldName();
        $html = "";
        $html .= "<div class=\"input-group\">\n";
        $html .= "<span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>\n";
        $html .= "<input type=\"text\" name=\"" . $fieldName . "_ini\" " .
            "class=\"form-control datepicker\" placeholder=\"Início\" size=\"10\" " .
            "value=\"" . $queryParam[$fieldName . "_ini"] . "\">\n";
        $html .= "<span class=\"input-group-addon\">até</span>\n";
        $html .= "<input type=\"text\" name=\"" . $fieldName . "_fim\" " .
            "class=\"form-control datepicker\" placeholder=\"Termíno\" size=\"10\" " .
            "value=\"" . $queryParam[$fieldName . "_fim"] . "\">\n";
        $html .= "<span class=\"input-group-btn\">\n";
        $html .= "<button type=\"submit\" class=\"btn btn-primary\"><i class=\"fa fa-search\"></i></button>\n";
        $html .= "</span>\n";
        $html .= "</div>\n";
        return $html;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function execute() {
        //$queryParam = $this->getQueryParam();
        $result = $this->executeQuery();
        $pg = $this->getCurrentPage();
        $pages = ceil($result->getTotal() / $this->getPageCount());
        //$urlPage = str_replace("%", "%%", $this->getPageUrl());
        $pagination = admin_pagination($pages, $this->getPageUrl(), $pg + 1);

        $html = "";
        $html .= $this->renderForm();
        if (count($result->getRows()) > 0) {
            $html .= $this->renderTable($result->getRows());
        }
        if ($pages > 1) {
            $html .= "<div class=\"text-center\">" . $pagination . "</div>";
        }
        return $html;
    }
}