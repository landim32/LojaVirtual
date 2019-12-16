<?php

namespace Emagine\Pedido;

use Exception;
use Emagine\Base\EmagineApp;
use Emagine\Login\Model\UsuarioInfo;
use Landim32\BtMenu\BtMenu;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Produto\BLL\LojaBLL;
use Emagine\Pedido\Model\PedidoHorarioInfo;
use Emagine\Pedido\Model\PedidoInfo;
use Emagine\Produto\Model\LojaInfo;

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
        if (count($lojas) > 0 && !is_null($menuMain)) {
            $menuPedido = $menuMain->addMenu(new BtMenu("Vendas", "#", "fa fa-shopping-cart"));
            if ($usuario->temPermissao(PedidoInfo::GERENCIAR_PEDIDO)) {
                $urlFormato = $app->getBaseUrl() . "/%s/pedidos";
                if (!is_null($lojaAtual)) {
                    $url = sprintf($urlFormato, $lojaAtual->getSlug());
                }
                elseif (count($lojas) > 1) {
                    $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
                }
                else {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                    $url = sprintf($urlFormato, $loja->getSlug());
                }
                $menuPedido->addSubMenu(new BtMenu("Pedidos", $url, "fa fa-fw fa-shopping-cart"));

                if (LojaBLL::usaRetiradaMapeada()) {
                    $urlFormato = $app->getBaseUrl() . "/%s/pedido/mapa";
                    if (!is_null($lojaAtual)) {
                        $url = sprintf($urlFormato, $lojaAtual->getSlug());
                    }
                    elseif (count($lojas) > 1) {
                        $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
                    }
                    else {
                        /** @var LojaInfo $loja */
                        $loja = array_values($lojas)[0];
                        $url = sprintf($urlFormato, $loja->getSlug());
                    }
                    $menuPedido->addSubMenu(new BtMenu(PedidoBLL::getRetiradaMapeadaTexto(), $url, "fa fa-fw fa-truck"));
                }

                $urlFormato = $app->getBaseUrl() . "/%s/pagamentos";
                if (!is_null($lojaAtual)) {
                    $url = sprintf($urlFormato, $lojaAtual->getSlug());
                }
                elseif (count($lojas) > 1) {
                    $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
                }
                else {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                    $url = sprintf($urlFormato, $loja->getSlug());
                }
                $menuPedido->addSubMenu(new BtMenu("Pagamentos", $url, "fa fa-fw fa-dollar"));
            }
            if ($usuario->temPermissao(PedidoHorarioInfo::GERENCIAR_HORARIO)) {
                $urlFormato = $app->getBaseUrl() . "/%s/horarios";
                if (!is_null($lojaAtual)) {
                    $url = sprintf($urlFormato, $lojaAtual->getSlug());
                }
                elseif (count($lojas) > 1) {
                    $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
                }
                else {
                    /** @var LojaInfo $loja */
                    $loja = array_values($lojas)[0];
                    $url = sprintf($urlFormato, $loja->getSlug());
                }
                $menuPedido->addSubMenu(new BtMenu("Horários", $url, "fa fa-fw fa-clock-o"));
            }

            /*
            $menuRelatorio = $menuMain->getByName("Relatórios");
            if (is_null($menuRelatorio)) {
                $menuRelatorio = $menuMain->addMenu(new BtMenu("Relatórios", "#", "fa fa-print"));
            }

            $urlFormato = $app->getBaseUrl() . "/%s/clientes";
            if (!is_null($lojaAtual)) {
                $url = sprintf($urlFormato, $lojaAtual->getSlug());
            }
            elseif (count($lojas) > 1) {
                $url = $app->getBaseUrl() . "/loja/seleciona?callback=" . urlencode($urlFormato);
            }
            else {
                //@var LojaInfo $loja
                $loja = array_values($lojas)[0];
                $url = sprintf($urlFormato, $loja->getSlug());
            }
            $menuRelatorio->addSubMenu(new BtMenu("Clientes", $url, "fa fa-fw fa-user-circle"));
            */
        }
    }
}
catch (Exception $e) {
    die($e->getMessage());
}
