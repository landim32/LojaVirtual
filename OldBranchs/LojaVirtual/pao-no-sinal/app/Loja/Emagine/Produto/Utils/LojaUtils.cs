using Acr.UserDialogs;
using Emagine.Base.Pages;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Mapa.Model;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Utils
{
    public static class LojaUtils
    {
        public static Page gerarEndereco() {
            return EnderecoUtils.gerarBuscaPorCep((endereco) =>
            {
                var regraUsuario = UsuarioFactory.create();
                var usuarioCep = regraUsuario.pegarAtual();
                if (usuarioCep == null)
                {
                    usuarioCep = new UsuarioInfo();
                }
                usuarioCep.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                regraUsuario.gravarAtual(usuarioCep);

                var lojaListaPage = new LojaListaPage
                {
                    Title = "Selecione sua Loja",
                    Local = new LocalInfo
                    {
                        Latitude = endereco.Latitude.Value,
                        Longitude = endereco.Longitude.Value
                    }
                };
                if (App.Current.MainPage is RootPage) {
                    ((RootPage)App.Current.MainPage).PushAsync(lojaListaPage);
                }
                else {
                    App.Current.MainPage = App.gerarRootPage(lojaListaPage);
                }
            });
        }

        public static Page gerarSelecionar() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario != null)
            {
                if (usuario.Enderecos.Count == 1)
                {
                    var endereco = usuario.Enderecos[0];
                    return new LojaListaPage
                    {
                        Title = "Selecione sua Loja",
                        Local = new LocalInfo
                        {
                            Latitude = endereco.Latitude.Value,
                            Longitude = endereco.Longitude.Value
                        }
                    };
                }
                else if (usuario.Enderecos.Count > 1)
                {
                    return EnderecoUtils.gerarEnderecoLista((endereco) =>
                    {
                        var lojaListaPage = new LojaListaPage
                        {
                            Title = "Selecione sua Loja",
                            Local = new LocalInfo
                            {
                                Latitude = endereco.Latitude.Value,
                                Longitude = endereco.Longitude.Value
                            }
                        };
                        if (App.Current.MainPage is RootPage) {
                            ((RootPage)App.Current.MainPage).PushAsync(lojaListaPage);
                        }
                        else {
                            App.Current.MainPage = App.gerarRootPage(lojaListaPage);
                        }
                    });
                }
                else
                {
                    return gerarEndereco();
                }
            }
            else
            {
                return gerarEndereco();
            }
        }

        public static async Task<Page> gerarLoja(bool exibeDestaque = true) {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja == null)
            {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    var lojas = await regraLoja.listar();
                    if (lojas.Count == 1)
                    {
                        regraLoja.gravarAtual(lojas[0]);
                        UserDialogs.Instance.HideLoading();
                        if (exibeDestaque) {
                            return ProdutoUtils.gerarProdutoListaDestaque();
                        }
                        else {
                            return ProdutoUtils.gerarProdutoListaPromocao();
                        }
                    }
                    else if (lojas.Count > 1) {
                        UserDialogs.Instance.HideLoading();
                        return gerarSelecionar();
                    }
                    else {
                        UserDialogs.Instance.HideLoading();
                        throw new Exception("Nenhuma loja ativa!");
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            }
            return ProdutoUtils.gerarProdutoListaDestaque();
        }
    }
}
