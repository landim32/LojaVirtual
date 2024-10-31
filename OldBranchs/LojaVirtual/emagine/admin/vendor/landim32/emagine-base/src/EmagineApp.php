<?php

namespace Emagine\Base;

use Exception;
use Slim\App;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\InvalidArgumentException;
use Landim32\BtMenu\BtMainMenu;
use Landim32\BtMenu\BtMenu;
use Emagine\Base\Controls\SettingCategory;
use Emagine\Base\Model\JavascriptInfo;

require __DIR__ . "/Core/function.inc.php";

class EmagineApp extends App
{
    const PHP = "php";
    const TWIG = "twig";

    private $jquery_ui_enabled = false;
    private $datatables_enabled = false;
    private $bootstrap_slider_enabled = false;
    private $autocomplete_enabled = false;

    private $modulos = array();
    private $styles = array();
    private $javascripts = array();
    private $menus = array();
    private $modals = array();
    private $settings = array();

    /**
     * Create new application
     *
     * @param ContainerInterface|array $container
     * @throws InvalidArgumentException
     */
    public function __construct($container = [])
    {
        //require __DIR__ . "/Core/function.inc.php";
        $this->inicializar();
        $this->inicializarIdioma();
        parent::__construct($container);
    }

    private function inicializarIdioma() {
        if (array_key_exists("idioma", $_GET)) {
            define("IDIOMA", $_GET["idioma"]);
        }
        else {
            define("IDIOMA", "pt_BR");
        }

        if (function_exists('bindtextdomain')) {
            if (IDIOMA == 'pt_BR') {
                putenv('LC_ALL='.IDIOMA.'.utf8');
                //setlocale(LC_ALL, IDIOMA.'.utf8');
                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            }
            else {
                putenv('LC_ALL='.IDIOMA);
                setlocale(LC_ALL, IDIOMA);
            }
            if (IDIOMA != 'en') {
                bindtextdomain('default', dirname(__DIR__).'/locale');
                bind_textdomain_codeset('default', 'UTF-8');
                textdomain('default');
            }
        }

        if (!function_exists("_")) :
            function _($text) {
                return $text;
            }
        endif;
    }

    private function inicializar() {
        set_time_limit(120);
        ini_set('memory_limit', '120M');
        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
        //setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * @return string
     */
    public function getTemaTipo() {
        if (defined("TEMA_TIPO")) {
            switch (strtolower(TEMA_TIPO)) {
                case EmagineApp::TWIG:
                    return EmagineApp::TWIG;
                    break;
                default:
                    return EmagineApp::PHP;
                    break;
            }
        }
        return EmagineApp::PHP;
    }

    /**
     * @return string
     */
    public function getTemaDir() {
        if (defined("TEMA_DIR")) {
            return TEMA_DIR;
        }
        $basePath = $this->getBasePath();
        return $basePath . "/templates";
    }

    /**
     * @return string
     */
    public function getCurrentUrl() {
        $basePath = $this->getBasePath();
        $dir = substr(__DIR__, strlen($basePath));
        $dir = str_replace("\\", "/", $dir);
        if (substr($dir, 0, 1) == "/") {
            $dir = substr($dir, 1);
        }
        if (substr($dir, -1, 1) == "/") {
            $dir = substr($dir, 0, strlen($dir) - 1);
        }
        return $this->getBaseUrl() . "/" . $dir;
    }

    /**
     * @param string $moduleDir
     * @return string
     */
    public function getModuleUrl($moduleDir) {
        $basePath = $this->getBasePath();
        $dir = substr($moduleDir, strlen($basePath));
        $dir = str_replace("\\", "/", $dir);
        if (substr($dir, 0, 1) == "/") {
            $dir = substr($dir, 1);
        }
        if (substr($dir, -1, 1) == "/") {
            $dir = substr($dir, 0, strlen($dir) - 1);
        }
        return $this->getBaseUrl() . "/" . $dir;
    }

    /**
     * @return bool
     */
    public function getJQueryUIEnabled() {
        return $this->jquery_ui_enabled;
    }

    /**
     * @param bool $value
     */
    public function setJQueryUIEnabled($value) {
        $this->jquery_ui_enabled = $value;
    }

    /**
     * @return bool
     */
    public function getDatatableEnabled() {
        return $this->datatables_enabled;
    }

    /**
     * @param bool $value
     */
    public function setDatatableEnabled($value) {
        $this->datatables_enabled = $value;
    }

    /**
     * @return bool
     */
    public function getBootstrapSliderEnabled() {
        return $this->bootstrap_slider_enabled;
    }

    /**
     * @param bool $value
     */
    public function setBootstrapSliderEnabled($value) {
        $this->bootstrap_slider_enabled = $value;
    }

    /**
     * @return bool
     */
    public function getAutoCompleteEnabled() {
        return $this->autocomplete_enabled;
    }

    /**
     * @param bool $value
     */
    public function setAutoCompleteEnabled($value) {
        $this->autocomplete_enabled = $value;
    }

    /**
     * @return string
     */
    public function getBasePath() {
        $container = $this->getContainer();
        $settings = $container->get('settings');
        return $settings['base_path'];
    }

    /**
     * @return string
     */
    public function getBaseUrl() {
        $container = $this->getContainer();
        $settings = $container->get('settings');
        return $settings['base_url'];
    }

    /**
     * @return string[]
     */
    public function getModules() {
        return $this->modulos;
    }

    /**
     * @param string $dirPath
     * @param string $webPath
     */
    public function includeModule($dirPath, $webPath) {
        $this->modulos[$dirPath] = $webPath;
    }

    /**
     * @return string[]
     */
    public function getModals() {
        return $this->modals;
    }

    /**
     * @param string $file
     */
    public function addModal($file) {
        $this->modals[] = $file;
    }

    /**
     * Renderiza os Modals
     */
    public function renderModal() {
        foreach ($this->getModals() as $fileModal) {
            if (file_exists($fileModal)) {
                require $fileModal;
            }
        }
    }

    /**
     * @param string $dir
     * @param string $ext
     * @return string[]
     */
    private function listFiles($dir, $ext) {
        $files = array();
        if (file_exists($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == $ext) {
                        $files[] = $file;
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }

    /**
     * @param string $dir
     * @return string[]
     */
    private function listRoutes($dir) {
        $files = array();
        if (file_exists($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    $file = strtolower($file);
                    $ext = substr($file, strrpos($file, '.') + 1);
                    if ($file != "." && $file != ".." && $ext == "php") {
                        $routeStart = "routes-";
                        $start = substr($file, 0, strlen($routeStart));
                        if ($start == $routeStart || $file == "routes.php") {
                            $files[] = $file;
                        } else {
                            $apiStart = "api-";
                            $start = substr($file, 0, strlen($apiStart));
                            if ($start == $apiStart || $file == "api.php") {
                                $files[] = $file;
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }

    /**
     * @param string $dir
     * @return string[]
     */
    private function listDependencies($dir) {
        $files = array();
        if (file_exists($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    $file = strtolower($file);
                    $ext = substr($file, strrpos($file, '.') + 1);
                    if ($file != "." && $file != ".." && $ext == "php") {
                        $routeStart = "dependencies-";
                        $start = substr($file, 0, strlen($routeStart));
                        if ($start == $routeStart || $file == "dependencies.php") {
                            $files[] = $file;
                        } else {
                            $apiStart = "dep-";
                            $start = substr($file, 0, strlen($apiStart));
                            if ($start == $apiStart || $file == "dep.php") {
                                $files[] = $file;
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }

    /**
     * @param string $dir
     * @return string[]
     */
    private function listMenu($dir) {
        $files = array();
        if (file_exists($dir)) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    $file = strtolower($file);
                    $ext = substr($file, strrpos($file, '.') + 1);
                    if ($file != "." && $file != ".." && $ext == "php") {
                        $routeStart = "menu-";
                        $start = substr($file, 0, strlen($routeStart));
                        if ($start == $routeStart || $file == "menu.php" || $file == "menus.php") {
                            $files[] = $file;
                        } else {
                            $apiStart = "menu-";
                            $start = substr($file, 0, strlen($apiStart));
                            if ($start == $apiStart || $file == "menu.php" || $file == "menus.php") {
                                $files[] = $file;
                            }
                        }
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }

    /**
     * Inicializar dependências
     */
    public function doDependencies() {

        $baseUrl = $this->getBaseUrl();
        $currentUrl = $this->getCurrentUrl();

        $this->addCss($baseUrl . "/css/main.min.css");
        $this->addJavascriptUrl($baseUrl . "/vendor/components/jquery/jquery.js");
        if ($this->getJQueryUIEnabled() == true) {
            $this->addCss($currentUrl . "/css/jquery-ui.min.css");
            $this->addJavascriptUrl($currentUrl . "/js/jquery-ui.min.js");
        }
        $this->addJavascriptUrl($baseUrl . "/vendor/components/bootstrap/js/bootstrap.min.js");

        $this->addCss($baseUrl . "/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css");
        $this->addJavascriptUrl($baseUrl . "/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js");
        if ($this->getAutoCompleteEnabled() == true) {
            $this->addCss($baseUrl . "/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css");
            $this->addJavascriptUrl($currentUrl . "/js/typeahead.bundle.min.js");
        }

        $this->addCss($baseUrl . "/node_modules/noty/lib/noty.css");
        $this->addJavascriptUrl($baseUrl . "/node_modules/noty/lib/noty.min.js");

        if ($this->getDatatableEnabled() == true) {
            $this->addCss($baseUrl . "/node_modules/datatables.net-bs/css/dataTables.bootstrap.css");
            $this->addJavascriptUrl($baseUrl . "/node_modules/datatables.net/js/jquery.dataTables.js");
            $this->addJavascriptUrl($baseUrl . "/node_modules/datatables.net-bs/js/dataTables.bootstrap.js");
        }

        $this->addCss($baseUrl . "/node_modules/fullcalendar/dist/fullcalendar.min.css");
        $this->addJavascriptUrl($baseUrl . "/node_modules/fullcalendar/node_modules/moment/min/moment-with-locales.min.js");
        $this->addJavascriptUrl($baseUrl . "/node_modules/fullcalendar/dist/fullcalendar.min.js");
        $this->addJavascriptUrl($baseUrl . "/node_modules/fullcalendar/dist/locale/pt-br.js");

        $this->addJavascriptUrl($baseUrl . "/node_modules/bootstrap-multiselect/dist/js/bootstrap-multiselect.js");
        $this->addJavascriptUrl($baseUrl . "/node_modules/nprogress-npm/nprogress.js");

        if ($this->getBootstrapSliderEnabled() == true) {
            $this->addCss($baseUrl . "/node_modules/bootstrap-slider/dist/css/bootstrap-slider.min.css");
            $this->addJavascriptUrl($baseUrl . "/node_modules/bootstrap-slider/dist/bootstrap-slider.js");
        }

        if ($this->getAutoCompleteEnabled() == true) {
            $this->addJavascriptUrl($currentUrl . "/js/bootstrap3-typeahead.min.js");
            $this->addJavascriptUrl($currentUrl . "/js/autocomplete.js");
        }

        //var_dump($currentUrl);
        $this->addJavascriptUrl($currentUrl . "/js/base.js");
        if ($this->getDatatableEnabled() == true) {
            $this->addJavascriptUrl($currentUrl . "/js/dataTable.js");
        }
        $this->addJavascriptUrl($currentUrl . "/js/fullCalendar.js");
        $this->addJavascriptUrl($currentUrl . "/js/multiselect.js");
        $this->addJavascriptUrl($currentUrl . "/js/jquery.maskmoney.min.js");

        //$this->addJavascriptUrl($currentUrl . "/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js");

        require __DIR__ . "/Core/dependencies.php";
        /*
        $container = $this->getContainer();
        $container['view'] = function ($container) {
            $basePath = $this->getBasePath();
            return new PhpRenderer($basePath . '/templates/');
        };
        */

        $modulos = $this->getModules();
        $this->loadDependencies();
        $this->loadMenu();
        foreach ($modulos as $moduloDir => $moduloWeb) {
            /*
            $moduloPath = $moduloDir . "/Core/dependencies.php";
            if (file_exists($moduloPath)) {
                require $moduloPath;
            }
            */
            $cssDir = $moduloDir . "/css";
            if (file_exists($cssDir)) {
                foreach ($this->listFiles($cssDir, 'css') as $file) {
                    $this->addCss($baseUrl . $moduloWeb . "/css/" . $file);
                }
            }
            $jsDir = $moduloDir . "/js";
            if (file_exists($jsDir)) {
                foreach ($this->listFiles($jsDir, 'js') as $file) {
                    $this->addJavascriptUrl($baseUrl . $moduloWeb . "/js/" . $file);
                }
            }
        }
        $menuRight = $this->getMenu("right");
        if (!is_null($menuRight)) {
            if (count($this->getSettings()) > 0) {
                $menuRight->insertMenu(new BtMenu("", $this->getBaseUrl() . "/painel-controle", "fa fa-cog"));
            }
        }
    }

    /**
     *
     */
    private function loadDependencies() {
        $modulos = $this->getModules();
        foreach ($modulos as $moduloDir => $moduloWeb) {
            $moduloPath = $moduloDir . "/Core";
            foreach ($this->listDependencies($moduloPath) as $depFile) {
                if (file_exists($moduloPath . "/" . $depFile)) {
                    require $moduloPath . "/" . $depFile;
                }
            }
        }
    }

    /**
     *
     */
    private function loadMenu() {
        $modulos = $this->getModules();
        foreach ($modulos as $moduloDir => $moduloWeb) {
            $moduloPath = $moduloDir . "/Core";
            foreach ($this->listMenu($moduloPath) as $menuFile) {
                if (file_exists($moduloPath . "/" . $menuFile)) {
                    require $moduloPath . "/" . $menuFile;
                }
            }
        }
    }

    /**
     * Inicializar rotas
     */
    public function doRoutes() {
        require __DIR__ . "/Core/routes.php";
        $modulos = $this->getModules();
        foreach ($modulos as $moduloDir => $moduloWeb) {
            $moduloPath = $moduloDir . "/Core";
            foreach ($this->listRoutes($moduloPath) as $routeFile) {
                if (file_exists($moduloPath . "/" . $routeFile)) {
                    require $moduloPath . "/" . $routeFile;
                }
            }
        }
    }

    /**
     * @return string[]
     */
    public function getCss() {
        return $this->styles;
    }

    /**
     * @param string $css
     */
    public function addCss($css) {
        if (!in_array($css, $this->styles)) {
            $this->styles[] = $css;
        }
    }

    /**
     * @param string $css
     * @return string
     */
    public function writeCss($css) {
        return sprintf("<link href=\"%s\" rel=\"stylesheet\" />\n", $css);
    }

    /**
     * @return string
     */
    public function renderCss() {
        $str = "";
        foreach ($this->styles as $css) {
            $str .= $this->writeCss($css);
        }
        return $str;
    }

    /**
     * @return JavascriptInfo[]
     */
    public function getJavascript() {
        return $this->javascripts;
    }

    /**
     * @param string $url
     * @return bool
     */
    private function hasJavascriptUrl($url) {
        $retorno = false;
        foreach ($this->getJavascript() as $js) {
            if ($js->getUrl() == $url) {
                $retorno = true;
                break;
            }
        }
        return $retorno;
    }

    /**
     * @param JavascriptInfo $js
     */
    public function addJavascript($js) {
        if (!isNullOrEmpty($js->getUrl())) {
            if (!$this->hasJavascriptUrl($js->getUrl())) {
                $this->javascripts[] = $js;
            }
        }
        elseif (!isNullOrEmpty($js->getConteudo())) {
            $this->javascripts[] = $js;
        }
    }

    /**
     * @param string $url
     */
    public function addJavascriptUrl($url) {
        $js = new JavascriptInfo();
        $js->setUrl($url);
        $this->addJavascript($js);
    }

    /**
     * @param string $conteudo
     */
    public function addJavascriptConteudo($conteudo) {
        $js = new JavascriptInfo();
        $js->setConteudo($conteudo);
        $this->addJavascript($js);
    }

    /**
     * @param JavascriptInfo $js
     * @return string
     */
    public function writeJavascript($js) {
        return $js;
    }

    /**
     * @return string
     */
    public function renderJavascript() {
        $str = "";
        foreach ($this->getJavascript() as $js) {
            if (!isNullOrEmpty($js->getUrl())) {
                $str .= $js;
            }
        }
        $str .= "<script type=\"text/javascript\">\n";
        $str .= sprintf("$.app.base_path = '%s';\n", $this->getBaseUrl());
        foreach ($this->getJavascript() as $js) {
            if (!isNullOrEmpty($js->getConteudo())) {
                $str .= $js->getConteudo() . "\n";
            }
        }
        if ($this->getBootstrapSliderEnabled() == true) {
            $str .= "\$(document).ready(function() {\n";
            $str .= "\t$(\"input.slider\").bootstrapSlider();\n";
            $str .= "});\n";
        }
        $str .= "</script>\n";
        return $str;
    }

    /**
     * @param string $id
     * @return BtMainMenu
     */
    public function getMenu($id) {
        return $this->menus[$id];
    }

    /**
     * @param string $id
     * @param BtMainMenu $value
     */
    public function setMenu($id, $value) {
        $this->menus[$id] = $value;
    }

    /**
     * @return SettingCategory[]
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * @param string $slug
     * @return SettingCategory
     */
    public function getSetting($slug) {
        return $this->settings[$slug];
    }

    /**
     * @param SettingCategory $category
     * @return SettingCategory
     */
    public function setSetting(SettingCategory $category) {
        $this->settings[$category->getSlug()] = $category;
        return $category;
    }

    /**
     * @param string $dirRaiz
     * @throws Exception
     * @return ContainerInterface|array
     */
    public static function getConfig($dirRaiz) {
        if (!defined("TEMA_PATH")) {
            throw new Exception("TEMA_PATH não foi definido.");
        }
        return array(
            'settings' => [
                'base_path' => $dirRaiz,
                'base_url' => TEMA_PATH,
                'displayErrorDetails' => true,
                'logger' => [
                    'name' => 'slim-app',
                    'level' => Logger::DEBUG,
                    'path' => __DIR__ . '/logs/app.log',
                ],
            ],
        );
    }

    /**
     * @return EmagineApp
     */
    public static function getApp() {
        return $GLOBALS["_app"];
    }

    /**
     * @param EmagineApp $value
     */
    public static function setApp($value) {
        $GLOBALS["_app"] = $value;
    }

    /**
     * @return bool
     */
    public static function usaCookie() {
        if (defined("USA_COOKIE")) {
            return (USA_COOKIE === true);
        }
        return true;
    }
}