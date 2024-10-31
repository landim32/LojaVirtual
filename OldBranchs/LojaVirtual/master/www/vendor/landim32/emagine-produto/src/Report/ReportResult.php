<?php
namespace Emagine\Produto\Report;


class ReportResult
{
    private $rows = array();
    private $total = array();

    /**
     * ReportResult constructor.
     * @param array $rows
     * @param int $total
     */
    public function __construct($rows, $total)
    {
        $this->rows = $rows;
        $this->total = $total;
    }

    /**
     * @return array
     */
    public function getRows() {
        return $this->rows;
    }

    public function getTotal() {
        return $this->total;
    }
}