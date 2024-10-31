<?php
namespace Emagine\Base\Controls;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SettingView extends ContentView
{
    private $settings;

    /**
     * @return SettingCategory[]
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * @param SettingCategory[] $value
     */
    public function setSettings($value) {
        $this->settings = $value;
    }

    /**
     * SettingView constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->setView("baseView");
        $this->setTemplate("painel-controle.php");
    }

    /**
     * @param SettingCategory $categoria
     * @param string $classe
     * @return string
     */
    private function renderPanel($categoria, $classe = "panel panel-default") {
        $html = "<div class='" . $classe . "'>\n";
        $html .= "<div class='panel-heading'>\n";
        $html .= "<h3 class='panel-title'>";
        if (!isNullOrEmpty($categoria->getIcon())) {
            $html .= "<i class='" . $categoria->getIcon() . "'></i> ";
        }
        $html .= $categoria->getName();
        $html .= "</h3>\n";
        $html .= "</div>\n";
        $html .= "<div class='panel-body'>\n";
        $quantidade = count($categoria->getLinks());
        if ($quantidade == 1) {
            /** @var SettingLink $link */
            $link = array_values($categoria->getLinks())[0];
            $html .= "<div class='text-center'>\n";
            $html .= $this->renderLink($link);
            $html .= "</div>\n";
        }
        elseif ($quantidade > 1) {
            $colClasse = "text-center";
            switch ($quantidade) {
                case 2:
                    $colClasse = "col-md-6";
                    break;
                case 3:
                    $colClasse = "col-md-4";
                    break;
                case 4:
                    $colClasse = "col-md-3";
                    break;
                case 5:
                case 6:
                    $colClasse = "col-md-2";
                    break;
            }
            $colClasse .= " text-center";
            $html .= "<div class='row'>\n";
            foreach ($categoria->getLinks() as $link) {
                $html .= sprintf("<div class='%s'>\n", $colClasse);
                $html .= $this->renderLink($link);
                $html .= "</div>\n";
            }
            $html .= "</div>\n";
        }
        $html .= "</div><!--panel-body-->\n";
        $html .= "</div><!--panel-->\n";
        return $html;
    }

    /**
     * @param SettingLink $link
     * @return string
     */
    private function renderLink($link) {
        $html = sprintf("<a href='%s'>", $link->getUrl());
        $html .= sprintf("<i class='%s fa-5x'></i><br />", $link->getIcon());
        $html .= sprintf("<strong>%s</strong></a>", $link->getName());
        return $html;
    }

    private function renderSettings() {
        $html = "<div class='row'>\n";
        foreach ($this->getSettings() as $categoria) {
            $html .= "<div class='col-md-6'>\n";
            $html .= $this->renderPanel($categoria);
            $html .= "</div>\n";
        }
        $html .= "</div>\n";
        return $html;
    }

    /**
     * @param string[] $args
     */
    protected function loadArgs(&$args)
    {
        $args['painel'] = $this->renderSettings();
    }
}