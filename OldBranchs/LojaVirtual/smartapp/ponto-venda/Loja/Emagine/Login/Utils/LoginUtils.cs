using Acr.UserDialogs;
using Emagine.Base.Pages;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Login.Utils
{
    public static class LoginUtils
    {
        /*
        public static Page gerarLogin(Action<UsuarioInfo> aoLogar)
        {
            var loginPage = new LoginPage
            {
                Title = "Login"
            };
            loginPage.AoLogar += (sender, usuario) =>
            {
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();

                if (loja != null)
                {
                    atualizarRootPage(ProdutoUtils.gerarProdutoListaDestaque(), false);
                }
                else
                {
                    if (usuario.Enderecos.Count > 0)
                    {
                        var endereco = usuario.Enderecos[0];
                        LojaUtils.gerarLojaLista(endereco);
                    }
                    else
                    {
                        EnderecoUtils.gerarBuscaPorCep((endereco) => {
                            LojaUtils.gerarLojaLista(endereco);
                        });
                    }
                }
            };
            return loginPage;
        }
        */

        public static void carregarUsuario(Action<UsuarioInfo> aoSelecionar)
        {
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
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).atualizarMenu();
                    }
                    aoSelecionar?.Invoke(usuarioRetorno);
                };
                if (App.Current.MainPage is RootPage)
                {
                    ((RootPage)App.Current.MainPage).PushAsync(usuarioSelecionaPage);
                }
                else {
                    App.Current.MainPage = App.gerarRootPage(usuarioSelecionaPage);
                    //App.Current.MainPage = new IconNavigationPage(usuarioSelecionaPage);
                }
            }
            else
            {
                aoSelecionar?.Invoke(usuario);
            }
        }

        public static Page gerarCadastro(Action<UsuarioInfo> aoCadastrar, bool usaRoot = true)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var usuarioCadastroPage = UsuarioFormPageFactory.create();
            usuarioCadastroPage.Title = "Cadastre-se";
            usuarioCadastroPage.Gravar = true;
            usuarioCadastroPage.Usuario = usuario;
            usuarioCadastroPage.AoCadastrar += (sender, e) => {
                aoCadastrar?.Invoke(e);
                //var destaquePage = ProdutoUtils.gerarProdutoListaDestaque();
                //pushAsync(destaquePage, usaRoot);
            };
            return usuarioCadastroPage;
        }
    }
}
