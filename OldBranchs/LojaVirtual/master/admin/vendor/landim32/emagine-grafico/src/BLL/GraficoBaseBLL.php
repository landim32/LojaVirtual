<?php
namespace Emagine\Grafico\BLL;

use Landim32\EasyDB\DB;
use Exception;
use pData;
use pImage;
use pPie;
use Emagine\Grafico\Model\EstatisticaInfo;

include(dirname(__DIR__) . "/pChart/class/pData.class.php");
include(dirname(__DIR__) . "/pChart/class/pDraw.class.php");
include(dirname(__DIR__) . "/pChart/class/pPie.class.php");
include(dirname(__DIR__) . "/pChart/class/pImage.class.php");

abstract class GraficoBaseBLL
{
    private $largura = 600;
    private $altura = 400;
    private $nome_arquivo;
    private $titulo;
    private $estatisticas;
    private $query;

    /**
     * GraficoBaseBLL constructor.
     * @param int $largura
     * @param int $altura
     * @param string $nomeArquivo
     */
    public function __construct($largura = 600, $altura = 400, $nomeArquivo = "")
    {
        $this->largura = $largura;
        $this->altura = $altura;
        $this->nome_arquivo = $nomeArquivo;
        $this->estatisticas = array();
    }

    /**
     * @return string
     */
    public function getNomeArquivo() {
        return $this->nome_arquivo;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setNomeArquivo($value) {
        $this->nome_arquivo = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitulo() {
        return $this->titulo;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setTitulo($value) {
        $this->titulo = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getLargura() {
        return $this->largura;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLargura($value) {
        $this->largura = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getAltura() {
        return $this->altura;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setAltura($value) {
        $this->altura = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setQuery($value) {
        $this->query = $value;
        return $this;
    }

    /**
     * @return EstatisticaInfo[]
     */
    public function listarDado() {
        return $this->estatisticas;
    }

    /**
     * @param $estatistica
     * @return $this
     */
    public function adicionarDado($estatistica) {
        $this->estatisticas[] = $estatistica;
        return $this;
    }

    /**
     * @return string[]
     */
    protected function listarLegenda() {
        $legendas = array();
        foreach ($this->listarDado() as $estatistica) {
            $legendas[] = $estatistica->getLegenda();
        }
        return $legendas;
    }

    /**
     * @return string[]
     */
    protected function listarValor() {
        $valores = array();
        foreach ($this->listarDado() as $estatistica) {
            $valores[] = $estatistica->getValor();
        }
        return $valores;
    }

    /**
     * @param pData $pData
     * @return pImage
     */
    protected function createPImage(pData $pData) {
        $myPicture = new pImage($this->getLargura(),$this->getAltura(), $pData);
        $myPicture->setFontProperties(
            array(
                "FontName" => dirname(__DIR__) . "/pChart/fonts/verdana.ttf",
                "FontSize"=>10, "R"=>80,"G"=>80,"B"=>80
            )
        );
        $myPicture->setShadow(true);
        return $myPicture;
    }


    /**
     * @throws Exception
     * @param string $legenda
     * @param string $valor
     * @return $this
     */
    public function executar($legenda = "legenda", $valor = "valor") {
        $db = DB::getDB()->prepare($this->getQuery());
        $lista = array();
        foreach (DB::getArray($db) as $row) {
            $lista[] = new EstatisticaInfo($row[$legenda], $row[$valor]);
        }
        $this->estatisticas = $lista;
        return $this;
    }

    /**
     * @return $this
     */
    public abstract function render();
}