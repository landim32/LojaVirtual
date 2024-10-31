<?php
namespace Emagine\Produto\Controls;

use Emagine\Base\EmagineApp;
use Emagine\Login\Factory\UsuarioPerfilFactory;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\BLL\LojaPerfilBLL;
use Emagine\Produto\BLL\UnidadeBLL;
use Emagine\Produto\Model\ProdutoInfo;
use Exception;
use Emagine\Base\Controls\ContentView;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\CategoriaBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Produto\BLL\ProdutoBLL;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProdutoFormControl extends ContentView
{
    private $produto;

    /**
     * @return ProdutoInfo
     */
    public function getProduto() {
        return $this->produto;
    }

    /**
     * @param ProdutoInfo $value
     */
    public function setProduto($value) {
        $this->produto = $value;
    }

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->setView("lojaView");
        $this->setTemplate("produto-form.php");
    }

    /**
     * @param string[] $args
     * @throws Exception
     */
    protected function loadArgs(&$args)
    {
        $app = EmagineApp::getApp();

        $regraLoja = new LojaBLL();
        $regraCategoria = new CategoriaBLL();
        $regraUsuario = new UsuarioBLL();
        $regraUnidade = new UnidadeBLL();

        $loja = $regraLoja->pegarPorSlug($args['slug_loja']);
        $categorias = $regraCategoria->listar($loja->getId());
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);
        $produto = $this->getProduto();
        $unidades = $regraUnidade->listar($loja->getId());

        $usuarioPerfil = UsuarioPerfilFactory::create();
        $usuarioPerfil->setUrlFormato($app->getBaseUrl() . "/%s/produtos");
        if ($usuarioPerfil instanceof LojaPerfilBLL) {
            $usuarioPerfil->setLoja($loja);
        }

        if (!is_null($produto) && $produto->getId() > 0) {
            $urlVoltar = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produto/" . $produto->getSlug();
        }
        else {
            $urlVoltar = $app->getBaseUrl() . "/" . $loja->getSlug() . "/produtos";
        }

        $args['app'] = $app;
        $args['loja'] = $loja;
        $args['unidades'] = $unidades;
        $args['produto'] = $produto;
        $args['categorias'] = $categorias;
        $args['usuarios'] = $usuarios;
        $args['urlVoltar'] = $urlVoltar;
        $args['usuarioPerfil'] = $usuarioPerfil;
    }
}