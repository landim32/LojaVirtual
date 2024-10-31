<?php
namespace Emagine\Base\Controls;

use Emagine\Base\EmagineApp;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

abstract class ContentView
{
    private $view;
    private $template;
    private $request;
    private $response;

    /**
     * EnderecoControl constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param string $value
     */
    public function setView($value) {
        $this->view = $value;
    }

    /**
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

    /**
     * @param string $value
     */
    public function setTemplate($value) {
        $this->template = $value;
    }

    protected abstract function loadArgs(&$args);

    /**
     * @throws Exception
     * @param string[] $args
     * @return Response
     */
    public function render($args) {
        $app = EmagineApp::getApp();

        $this->loadArgs($args);

        /** @var PhpRenderer $renderer */
        $renderer = $app->getContainer()->get($this->getView());
        $response = $renderer->render($this->response, $this->getTemplate(), $args);
        return $response;
    }
}