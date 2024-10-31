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
        public static void carregarUsuario(Action<UsuarioInfo> aoSelecionar) {
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
                PushAsync(usuarioSelecionaPage);
            }
            else {
                aoSelecionar?.Invoke(usuario);
            }
        }

        public static Page gerarLogin() {
            var loginPage = new LoginPage {
                Title = "Login"
            };
            loginPage.AoLogar += (sender, usuario) =>
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();

                if (loja != null)
                {
                    atualizarRootPage(ProdutoUtils.gerarProdutoListaDestaque());
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
                        });
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
                            });
                        });
                    }
                }
            };
            return loginPage;
        }

        public static Page gerarCadastro() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var usuarioCadastroPage = new UsuarioFormPage
            {
                Title = "Cadastre-se",
                Gravar = true,
                Usuario = usuario
            };
            usuarioCadastroPage.AoCadastrar += (sender, e) => {
                var destaquePage = ProdutoUtils.gerarProdutoListaDestaque();
                PushAsync(destaquePage);
            };
            return usuarioCadastroPage;
        }

        private static void PushAsync(Page paginaAtual)
        {
            if (App.Current.MainPage is RootPage)
            {
                ((RootPage)App.Current.MainPage).PushAsync(paginaAtual);
                ((RootPage)App.Current.MainPage).atualizarMenu();
            }
            else
            {
                App.Current.MainPage = App.gerarRootPage(paginaAtual);
            }
        }

        private static void atualizarRootPage(Page paginaAtual)
        {
            if (App.Current.MainPage is RootPage)
            {
                ((RootPage)App.Current.MainPage).PaginaAtual = paginaAtual;
                ((RootPage)App.Current.MainPage).atualizarMenu();
            }
            else
            {
                App.Current.MainPage = App.gerarRootPage(paginaAtual);
            }
        }
    }
}
