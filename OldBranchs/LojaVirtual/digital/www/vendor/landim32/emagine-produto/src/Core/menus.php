<?php

namespace Emagine\Produto;

use Emagine\Base\Controls\SettingCategory;
use Emagine\Base\Controls\SettingLink;
use Emagine\Produto\BLL\SeguimentoBLL;
use Emagine\Produto\Model\CategoriaInfo;
use Emagine\Produto\Model\LojaFreteInfo;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;
use Emagine\Produto\Model\SeguimentoInfo;
use Emagine\Produto\Model\UnidadeInfo;
use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioInfo;
use Landim32\BtMenu\BtMenu;
use Landim32\BtMenu\BtMenuSeparator;
use Slim\Http\UploadedFile;
use Slim\Views\PhpRenderer;
use Emagine\Produto\BLL\LojaBLL;

$app = EmagineApp::getApp();

try {
    $usuario = UsuarioBLL::pegarUsuarioAtual();
    if (!is_null($usuario)) {
        $regraLoja = new LojaBLL();
        $lojaAtual = $regraLoja->pegarAtual();
        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $lojas = $regraLoja->listar();
        } else {
            $lojas = $regraLoja->listarPorUsuario($usuario->getId());
        }
        if (LojaBLL::usaLojaUnica()) {
            $lojas = array_slice($lojas, 0, 1);
        }

        $menuMain = $app->getMenu("main");
        if ($usuario->temPermissao(LojaInfo::GERENCIAR_LOJA)) {
            $menuPrincipal = $menuMain->addMenu(new BtMenu("Lojas", "#", "fa fa-shopping-cart"));
            $menuPrincipal->addSubMenu(new BtMenu("Lojas", $app->getBaseUrl() . "/lojas", "fa fa-fw fa-shopping-cart"));
            $menuPrincipal->addSubMenu(new BtMenu("Nova Loja", $app->getBaseUrl() . "/loja/nova", "fa fa-fw fa-plus"));
        }
        $menuLoja = null;
        if ($usuario->temPermissao(ProdutoInfo::GERENCIAR_PRODUTO)) {
            $urlFormato = $app->getBaseUrl() . "/%s/produtos";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            } elseif (count($lojas) == 1) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
            if (is_null($menuLoja)) {
                $menuLoja = $menuMain->addMenu(new BtMenu("Produtos", "#", "fa fa-shopping-cart"));
            }
            $menuLoja->addSubMenu(new BtMenu("Produtos", $url, "fa fa-fw fa-shopping-cart"));
        }
        if ($usuario->temPermissao(CategoriaInfo::GERENCIAR_CATEGORIA)) {
            $urlFormato = $app->getBaseUrl() . "/%s/categorias";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            } elseif (count($lojas) == 1) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
            if (is_null($menuLoja)) {
                $menuLoja = $menuMain->addMenu(new BtMenu("Produtos", "#", "fa fa-shopping-cart"));
            }
            $menuLoja->addSubMenu(new BtMenu("Categorias", $url, "fa fa-fw fa-reorder"));
        }
        /*
        if ($usuario->temPermissao(UnidadeInfo::GERENCIAR_UNIDADE)) {
            $urlFormato = $app->getBaseUrl() . "/%s/unidades";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            } elseif (count($lojas) == 1) {
                # @var LojaInfo $loja
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
            if (is_null($menuLoja)) {
                $menuLoja = $menuMain->addMenu(new BtMenu("Produtos", "#", "fa fa-shopping-cart"));
            }
            $menuLoja->addSubMenu(new BtMenu("Unidades", $url, "fa fa-fw fa-balance-scale"));
        }
        */

        $menuRelatorio = $menuMain->addMenu(new BtMenu("Relatórios", "#", "fa fa-print"));

        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $url = $app->getBaseUrl() . "/relatorio/estoque";
        } else {
            $urlFormato = $app->getBaseUrl() . "/%s/relatorio/estoque";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            } elseif (count($lojas) == 1) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            }
            else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
        }
        $menuRelatorio->addSubMenu(new BtMenu("Estoque Diário", $url, "fa fa-fw fa-shopping-cart"));

        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $url = $app->getBaseUrl() . "/relatorio/valor-venda";
        } else {
            $urlFormato = $app->getBaseUrl() . "/%s/relatorio/valor-venda";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            } elseif (count($lojas) == 1) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
        }
        $menuRelatorio->addSubMenu(new BtMenu("Valores de Venda", $url, "fa fa-fw fa-dollar"));

        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $url = $app->getBaseUrl() . "/relatorio/venda-por-produto";
        } else {
            $urlFormato = $app->getBaseUrl() . "/%s/relatorio/venda-por-produto";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            }
            elseif (count($lojas) == 1) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
        }
        $menuRelatorio->addSubMenu(new BtMenu("Vendas por Produto", $url, "fa fa-fw fa-dollar"));

        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            $url = $app->getBaseUrl() . "/relatorio/clientes";
        } else {
            $urlFormato = $app->getBaseUrl() . "/%s/relatorio/clientes";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            }
            elseif (count($lojas) == 1) {
                /** @var LojaInfo $loja */
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
        }
        $menuRelatorio->addSubMenu(new BtMenu("Clientes", $url, "fa fa-fw fa-user"));

        $usuarioSetting = $app->setSetting(new SettingCategory("produto", "Minha Loja", "fa fa-shopping-cart"));
        if (count($lojas) == 1) {
            /** @var LojaInfo $loja */
            $loja = array_values($lojas)[0];
            if ($usuario->temPermissao(LojaInfo::CONFIGURA_LOJA)) {
                $urlLoja = $app->getBaseUrl() . "/loja/" . $loja->getSlug();
                $usuarioSetting->addLink(new SettingLink("minha-loja", "Configuração", $urlLoja, "fa fa-fw fa-cog"));
            }
            if ($usuario->temPermissao(LojaFreteInfo::GERENCIAR_FRETE)) {
                $urlFrete = $app->getBaseUrl() . "/" . $loja->getSlug() . "/fretes";
                $usuarioSetting->addLink(new SettingLink("frete", "Valores de Frete", $urlFrete, "fa fa-fw fa-truck"));
            }
            /*
            if ($usuario->temPermissao(UnidadeInfo::GERENCIAR_UNIDADE)) {
                $urlUnidade = $app->getBaseUrl() . "/" . $loja->getSlug() . "/unidades";
                $usuarioSetting->addLink(new SettingLink("unidade", "Unidades de Medida", $urlUnidade, "fa fa-fw fa-balance-scale"));
            }
            */
        } elseif (count($lojas) > 1) {
            $urlFormato = $app->getBaseUrl() . "/%s/fretes";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            } else {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
            $menuLoja->addSubMenu(new BtMenu("Valores de Frete", $url, "fa fa-fw fa-truck"));
        }

        if ($usuario->temPermissao(SeguimentoInfo::GERENCIAR_SEGUIMENTO)) {
            $urlLoja = $app->getBaseUrl() . "/seguimentos";
            $usuarioSetting->addLink(new SettingLink("seguimento", "Seguimentos", $urlLoja, "fa fa-fw fa-building"));
        }

        $menuLateral = $app->getMenu("lateral");
        if ($usuario->temPermissao(UsuarioInfo::ADMIN)) {
            if (!is_null($menuLateral)) {
                $menuLateral->addMenu(new BtMenu("Nova Loja", $app->getBaseUrl() . "/loja/nova", "fa fa-plus"));
            }
        }
    }
}
catch (Exception $e) {
    die($e->getMessage());
}