<?php
namespace Emagine\Grafico\BLL;

use pData;
use pImage;
use pPie;

class GraficoPizzaBLL extends GraficoBaseBLL
{
    private $pie_x = 500;
    private $pie_y = 180;
    private $legenda_x = 15;
    private $legenda_y = 40;
    private $draw_label = true;
    private $show_legenda = true;
    private $label_stacked = true;
    private $border = true;

    /**
     * @return int
     */
    public function getPieX() {
        return $this->pie_x;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setPieX($value) {
        $this->pie_x = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getPieY() {
        return $this->pie_y;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setPieY($value) {
        $this->pie_y = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getLegendaX() {
        return $this->legenda_x;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setLegendaX($value) {
        $this->legenda_x = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getLegendaY() {
        return $this->legenda_y;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setLegendaY($value) {
        $this->legenda_y = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDrawLabel() {
        return $this->draw_label;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setDrawLabel($value) {
        $this->draw_label = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getShowLegenda() {
        return $this->show_legenda;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setShowLegenda($value) {
        $this->show_legenda = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLabelStacked() {
        return $this->label_stacked;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setLabelStacked($value) {
        $this->label_stacked = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getBorder() {
        return $this->border;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function setBorder($value) {
        $this->border = $value;
        return $this;
    }

    /**
     * @return $this
     */
    public function render()
    {
        $MyData = new pData();
        $MyData->addPoints($this->listarValor(),"ScoreA");
        $MyData->setSerieDescription("ScoreA", "Application A");
        $MyData->addPoints($this->listarLegenda(),"Legendas");
        $MyData->setAbscissa("Legendas");
        $myPicture = $this->createPImage($MyData);

        $myPicture->setGraphArea(60,30,$this->getLargura() - 30,$this->getAltura() - 30);

        $PieChart = new pPie($myPicture, $MyData);
        $PieChart->draw3DPie(
            $this->getPieX(), $this->getPieY(),
            array(
                "DrawLabels" => $this->getDrawLabel(),
                "LabelStacked" => $this->getLabelStacked(),
                "Border" => $this->getBorder()
            )
        );
        if ($this->getShowLegenda() == true) {
            $PieChart->drawPieLegend($this->getLegendaX(), $this->getLegendaY(), array("Alpha" => 20));
        }
        $myPicture->autoOutput($this->getNomeArquivo());
        return $this;
    }
}