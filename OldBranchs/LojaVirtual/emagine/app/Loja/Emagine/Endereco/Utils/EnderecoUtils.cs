using Acr.UserDialogs;
using Emagine.Base.Pages;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Login.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Endereco.Utils
{
    public static class EnderecoUtils
    {
        public static Page gerarBuscaPorCep(Action<EnderecoInfo> aoSelecionar, bool usaLogin = true) {
            var cepPage = new CepPage {
                Title = "Entre com seu CEP",
                UsaBotaoLogin = usaLogin
            };
            cepPage.AoLogar += (sender, e) => {
                var loginPage = new LoginPage {
                    Title = "Login"
                };
                loginPage.AoLogar += (s2, usuario) => {
                    if (usuario.Enderecos.Count() == 1)
                    {
                        aoSelecionar?.Invoke(usuario.Enderecos[0]);
                    }
                    else if (usuario.Enderecos.Count() > 1)
                    {
                        ((Page)s2).Navigation.PushAsync(EnderecoUtils.gerarEnderecoLista((endereco) => {
                            aoSelecionar?.Invoke(endereco);
                        }));
                    }
                    else
                    {
                        ((Page)s2).Navigation.PushAsync(EnderecoUtils.gerarEnderecoLista((endereco) => {
                            aoSelecionar?.Invoke(endereco);
                        }), false);
                    }
                };
                ((Page)sender).Navigation.PushAsync(loginPage);
                //((Page)sender).Navigation.PushAsync(LoginUtils.gerarLogin((usuario) => {}));
            };
            cepPage.AoSelecionar += (s1, endereco) =>
            {
                var enderecoForm = new EnderecoFormPage
                {
                    Title = "Complete seu endereço",
                    PodeEditar = false,
                    Endereco = endereco
                };
                enderecoForm.AoSelecionar += (s2, endereco2) => {
                    ((Page)s2).Navigation.RemovePage(cepPage);
                    aoSelecionar(endereco2);
                    //((Page)s2).Navigation.RemovePage(enderecoForm);
                };
                cepPage.Navigation.PushAsync(enderecoForm);
            };
            cepPage.AoBuscar += (s0, e) => {
                var ufLista = new UfListaPage
                {
                    Title = "Selecione o Estado"
                };
                ufLista.AoSelecionar += (s1, estado) =>
                {
                    var cidadeBusca = new CidadeBuscaPage
                    {
                        Title = "Busque sua cidade",
                        Uf = estado.Uf
                    };
                    cidadeBusca.AoSelecionar += (s2, cidade) => {
                        var bairroBusca = new BairroBuscaPage
                        {
                            Title = "Busque seu bairro",
                            IdCidade = cidade.Id
                        };
                        bairroBusca.AoSelecionar += (s3, bairro) => {
                            var logradouroBusca = new LogradouroBuscaPage
                            {
                                Title = "Busque seu endereço",
                                IdBairro = bairro.Id
                            };
                            logradouroBusca.AoSelecionar += (s4, endereco) =>
                            {
                                var enderecoForm = new EnderecoFormPage
                                {
                                    Title = "Complete seu endereço",
                                    PodeEditar = false,
                                    Endereco = endereco
                                };
                                enderecoForm.AoSelecionar += (s5, endereco2) => {
                                    ((Page)s5).Navigation.RemovePage(cepPage);
                                    ((Page)s5).Navigation.RemovePage(ufLista);
                                    ((Page)s5).Navigation.RemovePage(cidadeBusca);
                                    ((Page)s5).Navigation.RemovePage(bairroBusca);
                                    ((Page)s5).Navigation.RemovePage(logradouroBusca);
                                    aoSelecionar(endereco2);
                                    //((Page)s5).Navigation.RemovePage(enderecoForm);
                                };
                                ((Page)s4).Navigation.PushAsync(enderecoForm);
                            };
                            ((Page)s3).Navigation.PushAsync(logradouroBusca);
                        };
                        ((Page)s2).Navigation.PushAsync(bairroBusca);
                    };
                    ((Page)s1).Navigation.PushAsync(cidadeBusca);
                };
                ((Page)s0).Navigation.PushAsync(ufLista);
            };
            return cepPage;
        }

        public static EnderecoListaPage gerarEnderecoLista(Action<EnderecoInfo> aoSelecionar)
        {
            var enderecoListaPage = new EnderecoListaPage
            {
                Title = "Endereços"
            };
            enderecoListaPage.AoAtualizar += async (sender, enderecos) => {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    var regraUsuario = UsuarioFactory.create();
                    var usuario = regraUsuario.pegarAtual();
                    usuario.Enderecos.Clear();
                    foreach (var endereco in enderecos)
                    {
                        usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                    }
                    int idUsuario = usuario.Id;
                    if (idUsuario > 0)
                    {
                        await regraUsuario.alterar(usuario);
                    }
                    else
                    {
                        idUsuario = await regraUsuario.inserir(usuario);
                    }
                    var usuarioRemoto = await regraUsuario.pegar(idUsuario);
                    regraUsuario.gravarAtual(usuarioRemoto);

                    var usuarioEnderecos = new List<EnderecoInfo>();
                    foreach (var endereco in usuarioRemoto.Enderecos)
                    {
                        usuarioEnderecos.Add(endereco);
                    }
                    ((EnderecoListaPage)sender).Enderecos = usuarioEnderecos;

                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
            };
            if (aoSelecionar != null)
            {
                enderecoListaPage.AoSelecionar += (sender, endereco) => {
                    aoSelecionar(endereco);
                };
            }
            return enderecoListaPage;
        }

        /*

        public static void selecionarEndereco(Action<EnderecoInfo> aoSelecionar)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario != null)
            {
                if (usuario.Enderecos.Count == 1)
                {
                    aoSelecionar?.Invoke(usuario.Enderecos[0]);
                }
                else if (usuario.Enderecos.Count > 1)
                {
                    var enderecoListaPage = EnderecoUtils.gerarEnderecoLista((endereco) => {
                        aoSelecionar?.Invoke(endereco);
                    });
                    var enderecos = new List<EnderecoInfo>();
                    foreach (var endereco in usuario.Enderecos)
                    {
                        enderecos.Add(endereco);
                    }
                    enderecoListaPage.Enderecos = enderecos;
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(enderecoListaPage);
                    }
                    else
                    {
                        App.Current.MainPage = App.gerarRootPage(enderecoListaPage);
                    }
                }
                else
                {
                    var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) => {
                        //var regraUsuario = UsuarioFactory.create();
                        var usuarioCep = regraUsuario.pegarAtual();
                        if (usuarioCep == null)
                        {
                            usuarioCep = new UsuarioInfo();
                        }
                        usuarioCep.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                        regraUsuario.gravarAtual(usuarioCep);

                        aoSelecionar?.Invoke(endereco);
                    });
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(cepPage);
                    }
                    else
                    {
                        App.Current.MainPage = App.gerarRootPage(cepPage);
                    }
                }
            }
            else
            {
                var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) => {
                    var usuarioCep = regraUsuario.pegarAtual();
                    if (usuarioCep == null)
                    {
                        usuarioCep = new UsuarioInfo();
                    }
                    usuarioCep.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                    regraUsuario.gravarAtual(usuarioCep);

                    aoSelecionar?.Invoke(endereco);
                });
                if (App.Current.MainPage is RootPage)
                {
                    ((RootPage)App.Current.MainPage).PushAsync(cepPage);
                }
                else
                {
                    App.Current.MainPage = App.gerarRootPage(cepPage);
                }
            }
        }
         */

        public static void selecionarEndereco(Action<EnderecoInfo> aoSelecionar)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario != null)
            {
                if (usuario.Enderecos.Count == 1)
                {
                    aoSelecionar?.Invoke(usuario.Enderecos[0]);
                }
                else if (usuario.Enderecos.Count > 1)
                {
                    var enderecoListaPage = EnderecoUtils.gerarEnderecoLista((endereco) => {
                        aoSelecionar?.Invoke(endereco);
                    });
                    var enderecos = new List<EnderecoInfo>();
                    foreach (var endereco in usuario.Enderecos)
                    {
                        enderecos.Add(endereco);
                    }
                    enderecoListaPage.Enderecos = enderecos;
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(enderecoListaPage);
                    }
                    else
                    {
                        App.Current.MainPage = App.gerarRootPage(enderecoListaPage);
                    }
                }
                else
                {
                    var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) => {
                        //var regraUsuario = UsuarioFactory.create();
                        var usuarioCep = regraUsuario.pegarAtual();
                        if (usuarioCep == null)
                        {
                            usuarioCep = new UsuarioInfo();
                        }
                        usuarioCep.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                        regraUsuario.gravarAtual(usuarioCep);

                        aoSelecionar?.Invoke(endereco);
                    });
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(cepPage);
                    }
                    else
                    {
                        App.Current.MainPage = App.gerarRootPage(cepPage);
                    }
                }
            }
            else
            {
                var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) => {
                    var usuarioCep = regraUsuario.pegarAtual();
                    if (usuarioCep == null)
                    {
                        usuarioCep = new UsuarioInfo();
                    }
                    usuarioCep.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                    regraUsuario.gravarAtual(usuarioCep);

                    aoSelecionar?.Invoke(endereco);
                });
                if (App.Current.MainPage is RootPage)
                {
                    ((RootPage)App.Current.MainPage).PushAsync(cepPage);
                }
                else
                {
                    App.Current.MainPage = App.gerarRootPage(cepPage);
                }
            }
        }
    }
}
