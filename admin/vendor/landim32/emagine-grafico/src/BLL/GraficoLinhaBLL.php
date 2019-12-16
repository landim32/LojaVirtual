<?php
namespace Emagine\Grafico\BLL;

use pData;
use pImage;

class GraficoLinhaBLL extends GraficoBaseBLL
{
    public function render()
    {
        $MyData = new pData();
        $MyData->addPoints($this->listarValor(),"Probe 1");
        //$MyData->setSerieTicks("Probe 1",4);
        $MyData->setSerieWeight("Probe 1",2);
        if (!isNullOrEmpty($this->getTitulo())) {
            $MyData->setAxisName(0, $this->getTitulo());
        }
        $MyData->addPoints($this->listarLegenda(),"Labels");
        $MyData->setSerieDescription("Labels","Months");
        $MyData->setAbscissa("Labels");

        $myPicture = $this->createPImage($MyData);

        $myPicture->setGraphArea(60,30,$this->getLargura() - 30,$this->getAltura() - 30);
        $myPicture->drawScale(array("DrawSubTicks"=>TRUE));
        $myPicture->setShadow(TRUE, array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
        $myPicture->drawLineChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO));
        //$myPicture->setShadow(FALSE);

        $myPicture->autoOutput($this->getNomeArquivo());
    }
}