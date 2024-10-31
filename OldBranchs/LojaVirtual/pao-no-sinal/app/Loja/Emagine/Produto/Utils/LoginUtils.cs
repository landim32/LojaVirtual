using Emagine;
using Emagine.Base.Pages;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Mapa.Model;
using Emagine.Produto.Factory;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Utils
{
    public static class LoginUtils
    {
        public static void carregarUsuario(Action<UsuarioInfo> aoSelecionar, bool usaRoot = true) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario == null || (usuario != null && usuario.Id <= 0))
            {
                var usuarioSelecionaPage = new UsuarioSelecionaPage
                {
                    Title = "Crie / Use sua conta",
                    Usuario = usuario
                };
                usuarioSelecionaPage.AoSelecionar += (sender, usuarioRetorno) =>
                {
                    if (App.Current.MainPage is RootPage) {
                        ((RootPage)App.Current.MainPage).atualizarMenu();
                    }
                    aoSelecionar?.Invoke(usuarioRetorno);
                };
                pushAsync(usuarioSelecionaPage, usaRoot);
            }
            else {
                aoSelecionar?.Invoke(usuario);
            }
        }

        public static Page gerarLogin(bool usaRoot = true) {
            var loginPage = new LoginPage {
                Title = "Login"
            };
            loginPage.AoLogar += (sender, usuario) =>
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();

                if (loja != null)
                {
                    atualizarRootPage(ProdutoUtils.gerarProdutoListaDestaque(), usaRoot);
                }
                else {
                    if (usuario.Enderecos.Count > 0)
                    {
                        var endereco = usuario.Enderecos[0];
                        atualizarRootPage(new LojaListaPage
                        {
                            Title = "Selecione sua Loja",
                            Local = new LocalInfo
                            {
                                Latitude = endereco.Latitude.Value,
                                Longitude = endereco.Longitude.Value
                            }
                        }, usaRoot);
                    }
                    else {
                        EnderecoUtils.gerarBuscaPorCep((endereco) => {
                            atualizarRootPage(new LojaListaPage
                            {
                                Title = "Selecione sua Loja",
                                Local = new LocalInfo
                                {
                                    Latitude = endereco.Latitude.Value,
                                    Longitude = endereco.Longitude.Value
                                }
                            }, usaRoot);
                        });
                    }
                }
            };
            return loginPage;
        }

        public static Page gerarCadastro(bool usaRoot = true) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var usuarioCadastroPage = UsuarioFormPageFactory.create();
            usuarioCadastroPage.Title = "Cadastre-se";
            usuarioCadastroPage.Gravar = true;
            usuarioCadastroPage.Usuario = usuario;
            usuarioCadastroPage.AoCadastrar += (sender, e) => {
                var destaquePage = ProdutoUtils.gerarProdutoListaDestaque();
                pushAsync(destaquePage, usaRoot);
            };
            return usuarioCadastroPage;
        }

        private static void pushAsync(Page paginaAtual, bool usaRoot)
        {
            if (App.Current.MainPage is RootPage)
            {
                ((RootPage)App.Current.MainPage).PushAsync(paginaAtual);
                ((RootPage)App.Current.MainPage).atualizarMenu();
            }
            else
            {
                if (usaRoot)
                {
                    App.Current.MainPage = App.gerarRootPage(paginaAtual);
                }
                else {
                    if (App.Current.MainPage != null)
                    {
                        App.Current.MainPage.Navigation.PushAsync(paginaAtual);
                    }
                    else {
                        App.Current.MainPage = new NavigationPage(paginaAtual);
                    }
                }
            }
        }

        private static void atualizarRootPage(Page paginaAtual, bool usaRoot)
        {
            if (App.Current.MainPage is RootPage)
            {
                ((RootPage)App.Current.MainPage).PaginaAtual = paginaAtual;
                ((RootPage)App.Current.MainPage).atualizarMenu();
            }
            else {
                if (usaRoot) {
                    App.Current.MainPage = App.gerarRootPage(paginaAtual);
                }
                else {
                    if (App.Current.MainPage != null) {
                        App.Current.MainPage.Navigation.PushAsync(paginaAtual);
                    }
                    else {
                        App.Current.MainPage = new NavigationPage(paginaAtual);
                    }
                }
            }
        }
    }
}
