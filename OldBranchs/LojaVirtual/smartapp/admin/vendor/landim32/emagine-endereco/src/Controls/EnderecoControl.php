<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 19/04/2018
 * Time: 07:46
 */

namespace Emagine\Endereco\Controls;

use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Endereco\BLL\CepBLL;
use Emagine\Endereco\BLL\EnderecoBLL;
use Emagine\Endereco\Model\EnderecoInfo;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class EnderecoControl
{
    const FORM_GROUP_MD = "form-group";
    const FORM_GROUP_LG = "form-group form-group-lg";

    const TEMPLATE_FORM_HORIZONTAL = "endereco-form-horizontal.php";
    const TEMPLATE_BUSCA_HORIZONTAL = "endereco-busca-horizontal.php";

    private $formGroupClasse = "form-group";
    private $view = "localidadeView";
    private $template;
    private $exibeCep = true;
    private $exibePosicao = true;
    private $exibeComplemento = true;
    private $logradouroEditavel = true;
    private $bairroEditavel = true;
    private $cidadeEditavel = true;
    private $ufEditavel = true;
    private $posicaoEditavel = true;
    private $request;
    private $response;
    private $endereco;

    /**
     * EnderecoControl constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->setTemplate(EnderecoControl::TEMPLATE_FORM_HORIZONTAL);
    }

    /**
     * @return string
     */
    public function getFormGroupClasse() {
        return $this->formGroupClasse;
    }

    /**
     * @param string $value
     */
    public function setFormGroupClasse($value) {
        $this->formGroupClasse = $value;
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

    /**
     * @return bool
     */
    public function getExibeCep() {
        return $this->exibeCep;
    }

    /**
     * @param bool $value
     */
    public function setExibeCep($value) {
        $this->exibeCep = $value;
    }

    /**
     * @return bool
     */
    public function getExibePosicao() {
        return $this->exibePosicao;
    }

    /**
     * @param bool $value
     */
    public function setExibePosicao($value) {
        $this->exibePosicao = $value;
    }

    /**
     * @return bool
     */
    public function getExibeComplemento() {
        return $this->exibeComplemento;
    }

    /**
     * @param bool $value
     */
    public function setExibeComplemento($value) {
        $this->exibeComplemento = $value;
    }

    /**
     * @return bool
     */
    public function getLogradouroEditavel() {
        return $this->logradouroEditavel;
    }

    /**
     * @param bool $value
     */
    public function setLogradouroEditavel($value) {
        $this->logradouroEditavel = $value;
    }

    /**
     * @return bool
     */
    public function getBairroEditavel() {
        return $this->bairroEditavel;
    }

    /**
     * @param bool $value
     */
    public function setBairroEditavel($value) {
        $this->bairroEditavel = $value;
    }

    /**
     * @return bool
     */
    public function getCidadeEditavel() {
        return $this->cidadeEditavel;
    }

    /**
     * @param bool $value
     */
    public function setCidadeEditavel($value) {
        $this->cidadeEditavel = $value;
    }

    /**
     * @return bool
     */
    public function getUfEditavel() {
        return $this->ufEditavel;
    }

    /**
     * @param bool $value
     */
    public function setUfEditavel($value) {
        $this->ufEditavel = $value;
    }

    /**
     * @return bool
     */
    public function getPosicaoEditavel() {
        return $this->posicaoEditavel;
    }

    /**
     * @param bool $value
     */
    public function setPosicaoEditavel($value) {
        $this->posicaoEditavel = $value;
    }

    /**
     * @return EnderecoInfo
     */
    public function getEndereco() {
        if (is_null($this->endereco)) {
            $this->endereco = new EnderecoInfo();
        }
        $postData = $this->request->getParsedBody();
        if (is_null($postData)) {
            $postData = array();
        }
        $regraEndereco = new EnderecoBLL();
        $this->endereco = $regraEndereco->pegarDoPost($postData, $this->endereco);
        return $this->endereco;
    }

    /**
     * @param EnderecoInfo $value
     */
    public function setEndereco($value) {
        $this->endereco = $value;
    }

    /**
     * @throws Exception
     * @param string[] $args
     * @return Response
     */
    public function render($args) {
        $app = EmagineApp::getApp();

        $regraCep = new CepBLL();
        $estados = $regraCep->listarUf();

        $args['endereco'] = $this->getEndereco();
        $args['estados'] = $estados;
        $args['formGroupClasse'] = $this->getFormGroupClasse();
        $args['exibeCep'] = $this->getExibeCep();
        $args['exibePosicao'] = $this->getExibePosicao();
        $args['exibeComplemento'] = $this->getExibeComplemento();
        $args['logradouroEditavel'] = $this->getLogradouroEditavel();
        $args['bairroEditavel'] = $this->getBairroEditavel();
        $args['cidadeEditavel'] = $this->getCidadeEditavel();
        $args['ufEditavel'] = $this->getUfEditavel();
        $args['posicaoEditavel'] = $this->getPosicaoEditavel();

        /** @var PhpRenderer $renderer */
        $renderer = $app->getContainer()->get($this->getView());
        $response = $renderer->render($this->response, $this->getTemplate(), $args);
        return $response;
    }
}