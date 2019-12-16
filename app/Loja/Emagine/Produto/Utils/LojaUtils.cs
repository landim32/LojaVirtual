using Acr.UserDialogs;
using Emagine.Banner.Factory;
using Emagine.Banner.Model;
using Emagine.Banner.Utils;
using Emagine.Base.Pages;
using Emagine.Endereco.Model;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Login.Utils;
using Emagine.Mapa.Model;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Utils
{
    public static class LojaUtils
    {
        /*
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
                    Title = "Selecione sua Loja"
                };
                lojaListaPage.AoCarregar += async (sender, e) =>
                {
                    var regraLoja = LojaFactory.create();
                    var regraBanner = BannerPecaFactory.create();
                    e.Banners = await regraBanner.gerar(new BannerFiltroInfo {
                        SlugBanner = BannerUtils.SEGUIMENTO,
                        Latitude = endereco.Latitude.Value,
                        Longitude = endereco.Longitude.Value,
                        Raio = regraLoja.RaioBusca
                    });
                    e.Lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca);
                };
                if (App.Current.MainPage is RootPage) {
                    ((RootPage)App.Current.MainPage).PushAsync(lojaListaPage);
                }
                else {
                    App.Current.MainPage = App.gerarRootPage(lojaListaPage);
                }
            });
        }
        */
        
        /*
        public static Page gerarSelecionar() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario != null)
            {
                if (usuario.Enderecos.Count == 1)
                {
                    var endereco = usuario.Enderecos[0];
                    var lojaListaPage = new LojaListaPage {
                        Title = "Selecione sua Loja"
                    };
                    lojaListaPage.AoCarregar += async (sender, e) =>
                    {
                        var regraLoja = LojaFactory.create();
                        var regraBanner = BannerPecaFactory.create();
                        e.Banners = await regraBanner.gerar(new BannerFiltroInfo
                        {
                            SlugBanner = BannerUtils.SEGUIMENTO,
                            Latitude = endereco.Latitude.Value,
                            Longitude = endereco.Longitude.Value,
                            Raio = regraLoja.RaioBusca
                        });
                        e.Lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca);
                    };
                    return lojaListaPage;
                }
                else if (usuario.Enderecos.Count > 1)
                {
                    //return EnderecoUtils.gerarEnderecoLista((endereco) =>
                    var enderecoListaPage = EnderecoUtils.gerarEnderecoLista((endereco) =>
                    {
                        var lojaListaPage = new LojaListaPage
                        {
                            Title = "Selecione sua Loja"
                        };
                        lojaListaPage.AoCarregar += async (sender, e) =>
                        {
                            var regraLoja = LojaFactory.create();
                            var regraBanner = BannerPecaFactory.create();
                            e.Banners = await regraBanner.gerar(new BannerFiltroInfo
                            {
                                SlugBanner = BannerUtils.SEGUIMENTO,
                                Latitude = endereco.Latitude.Value,
                                Longitude = endereco.Longitude.Value,
                                Raio = regraLoja.RaioBusca
                            });
                            e.Lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca);
                        };
                        if (App.Current.MainPage is RootPage) {
                            ((RootPage)App.Current.MainPage).PushAsync(lojaListaPage);
                        }
                        else {
                            App.Current.MainPage = App.gerarRootPage(lojaListaPage);
                        }
                    });
                    var enderecos = new List<EnderecoInfo>();
                    foreach (var endereco in usuario.Enderecos) {
                        enderecos.Add(endereco);
                    }
                    enderecoListaPage.Enderecos = enderecos;
                    return enderecoListaPage;
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
        */

        public static async Task<Page> gerarTelaInicial() {
            UserDialogs.Instance.ShowLoading("Carregando...");
            var telaInicialPage = new TelaInicialPage
            {
                Title = "Selecione o seguimento"
            };
            try
            {
                var regraLoja = LojaFactory.create();
                var regraBanner = BannerPecaFactory.create();
                var regraSeguimento = SeguimentoFactory.create();
                telaInicialPage.Banners = await regraBanner.gerar(new BannerFiltroInfo
                {
                    SlugBanner = BannerUtils.TELA_INICIAL,
                    Ordem = BannerOrdemEnum.Aleatorio
                });
                telaInicialPage.Seguimentos = await regraSeguimento.listar();
                telaInicialPage.AoBuscarPorRaio += (sender, raio) => {
                    regraLoja.RaioBusca = raio;
                    EnderecoUtils.selecionarEndereco(async (endereco) => {
                        var seguimentoPage = await gerarSeguimento(endereco);
                        if (App.Current.MainPage is RootPage) {
                            ((RootPage)App.Current.MainPage).PushAsync(seguimentoPage);
                        }
                        else {
                            App.Current.MainPage = App.gerarRootPage(seguimentoPage);
                        }
                    });
                };
                telaInicialPage.AoClicar += (sender, seguimento) => {
                    if (seguimento.ApenasPJ) {
                        LoginUtils.carregarUsuario((usuario) => {
                            if (usuario.PJ.HasValue && usuario.PJ.Value) {
                                EnderecoUtils.selecionarEndereco(async (endereco) => {
                                    /*
                                    var seguimentoPage = await gerarSeguimento(endereco);
                                    if (App.Current.MainPage is RootPage) {
                                        ((RootPage)App.Current.MainPage).PushAsync(seguimentoPage);
                                    }
                                    else {
                                        App.Current.MainPage = App.gerarRootPage(seguimentoPage);
                                    }
                                    */
                                    try {
                                        UserDialogs.Instance.ShowLoading("Carregando...");
                                        var lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca, seguimento.Id);
                                        if (lojas.Count > 0)
                                        {
                                            //var seguimentoPage = await gerarSeguimento(endereco);
                                            var lojaListaPage = await LojaUtils.gerarLojaLista(seguimento, endereco, lojas);
                                            UserDialogs.Instance.HideLoading();
                                            if (App.Current.MainPage is RootPage) {
                                                ((RootPage)App.Current.MainPage).PushAsync(lojaListaPage);
                                            }
                                            else {
                                                App.Current.MainPage = App.gerarRootPage(lojaListaPage);
                                            }
                                        }
                                        else
                                        {
                                            UserDialogs.Instance.HideLoading();
                                            await UserDialogs.Instance.AlertAsync("Você deve aumentar o raio da busca ou aguardar futura loja no seguimento.");
                                        }
                                    }
                                    catch (Exception erro)
                                    {
                                        UserDialogs.Instance.HideLoading();
                                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                                    }
                                });
                            }
                            else {
                                UserDialogs.Instance.AlertAsync("Essa é uma área apenas para pessoas jurídicas.");
                            }
                        });
                    }
                    else {
                        EnderecoUtils.selecionarEndereco(async (endereco) => {
                            try {
                                UserDialogs.Instance.ShowLoading("Carregando...");
                                var lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca, seguimento.Id);
                                if (lojas.Count > 0) {
                                    //var seguimentoPage = await gerarSeguimento(endereco);
                                    var lojaListaPage = await LojaUtils.gerarLojaLista(seguimento, endereco, lojas);
                                    UserDialogs.Instance.HideLoading();
                                    if (App.Current.MainPage is RootPage)
                                    {
                                        ((RootPage)App.Current.MainPage).PushAsync(lojaListaPage);
                                    }
                                    else
                                    {
                                        App.Current.MainPage = App.gerarRootPage(lojaListaPage);
                                    }
                                }
                                else {
                                    UserDialogs.Instance.HideLoading();
                                    await UserDialogs.Instance.AlertAsync("Você deve aumentar o raio da busca ou aguardar futura loja no seguimento.");
                                }
                            }
                            catch (Exception erro)
                            {
                                UserDialogs.Instance.HideLoading();
                                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                            }
                        });
                    }
                };
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro) {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
            return telaInicialPage;
        }

        public static async Task<Page> gerarSeguimento(EnderecoInfo endereco) {
            UserDialogs.Instance.ShowLoading("Carregando...");
            var seguimentoPage = new SeguimentoListaPage {
                Title = "Selecione o seguimento"
            };
            try {
                var regraBanner = BannerPecaFactory.create();
                var regraSeguimento = SeguimentoFactory.create();
                var regraLoja = LojaFactory.create();
                seguimentoPage.Banners = await regraBanner.gerar(new BannerFiltroInfo
                {
                    /*
                    SlugBanner = BannerUtils.SEGUIMENTOS,
                    Latitude = endereco.Latitude.Value,
                    Longitude = endereco.Longitude.Value,
                    Raio = regraLoja.RaioBusca,
                    Ordem = BannerOrdemEnum.Aleatorio
                    */
                    SlugBanner = BannerUtils.SEGUIMENTOS,
                    Ordem = BannerOrdemEnum.Aleatorio
                });
                seguimentoPage.Seguimentos = await regraSeguimento.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca);
                seguimentoPage.AoBuscarPorRaio += async (sender, raio) => {
                    UserDialogs.Instance.ShowLoading("Carregando...");
                    try
                    {
                        regraLoja.RaioBusca = raio;
                        seguimentoPage.Banners = await regraBanner.gerar(new BannerFiltroInfo
                        {
                            /*
                            SlugBanner = BannerUtils.SEGUIMENTOS,
                            Latitude = endereco.Latitude.Value,
                            Longitude = endereco.Longitude.Value,
                            Raio = regraLoja.RaioBusca,
                            Ordem = BannerOrdemEnum.Aleatorio
                            */
                            SlugBanner = BannerUtils.SEGUIMENTOS,
                            Ordem = BannerOrdemEnum.Aleatorio
                        });
                        seguimentoPage.Seguimentos = await regraSeguimento.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca);
                        UserDialogs.Instance.HideLoading();
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                    }
                };
                seguimentoPage.AoClicar += async (sender, seguimento) => {
                    if (seguimento.ApenasPJ)
                    {
                        LoginUtils.carregarUsuario( async (usuario) => {
                            if (usuario.PJ.HasValue && usuario.PJ.Value)
                            {
                                try
                                {
                                    UserDialogs.Instance.ShowLoading("Carregando...");
                                    var lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca, seguimento.Id);
                                    if (lojas.Count > 0)
                                    {
                                        var lojaListaPage = await LojaUtils.gerarLojaLista(seguimento, endereco, lojas);
                                        UserDialogs.Instance.HideLoading();
                                        await seguimentoPage.Navigation.PushAsync(lojaListaPage);
                                    }
                                    else
                                    {
                                        UserDialogs.Instance.HideLoading();
                                        await UserDialogs.Instance.AlertAsync("Você deve aumentar o raio da busca ou aguardar futura loja no seguimento.");
                                    }
                                }
                                catch (Exception erro)
                                {
                                    UserDialogs.Instance.HideLoading();
                                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                                }

                                //var lojaListaPage = gerarLojaLista(seguimento, endereco);
                                //seguimentoPage.Navigation.PushAsync(lojaListaPage);
                            }
                            else {
                                await UserDialogs.Instance.AlertAsync("Essa é uma área apenas para pessoas jurídicas.");
                            }
                        });
                    }
                    else {
                        //var lojaListaPage = gerarLojaLista(seguimento, endereco);
                        //seguimentoPage.Navigation.PushAsync(lojaListaPage);
                        try
                        {
                            UserDialogs.Instance.ShowLoading("Carregando...");
                            var lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca, seguimento.Id);
                            if (lojas.Count > 0)
                            {
                                var lojaListaPage = await LojaUtils.gerarLojaLista(seguimento, endereco, lojas);
                                UserDialogs.Instance.HideLoading();
                                await seguimentoPage.Navigation.PushAsync(lojaListaPage);
                            }
                            else
                            {
                                UserDialogs.Instance.HideLoading();
                                await UserDialogs.Instance.AlertAsync("Você deve aumentar o raio da busca ou aguardar futura loja no seguimento.");
                            }
                        }
                        catch (Exception erro)
                        {
                            UserDialogs.Instance.HideLoading();
                            UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                        }
                    }
                };
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
            return seguimentoPage;
        }

        //public static void gerarLojaLista(EnderecoInfo endereco) {
        public static async Task<LojaListaPage> gerarLojaLista(SeguimentoInfo seguimento, EnderecoInfo endereco, IList<LojaInfo> lojas) {
            var regraLoja = LojaFactory.create();
            var regraBanner = BannerPecaFactory.create();
            var lojaListaPage = new LojaListaPage
            {
                Title = "Selecione sua Loja",
                Banners = await regraBanner.gerar(new BannerFiltroInfo
                {
                    SlugBanner = BannerUtils.SEGUIMENTO,
                    IdSeguimento = seguimento.Id,
                    Latitude = endereco.Latitude.Value,
                    Longitude = endereco.Longitude.Value,
                    Raio = regraLoja.RaioBusca
                }),
                Lojas = lojas
            };
            /*
            lojaListaPage.AoCarregar += async (sender, e) =>
            {
                var regraLoja = LojaFactory.create();
                var regraBanner = BannerPecaFactory.create();
                e.Banners = await regraBanner.gerar(new BannerFiltroInfo
                {
                    SlugBanner = BannerUtils.SEGUIMENTO,
                    IdSeguimento = seguimento.Id,
                    Latitude = endereco.Latitude.Value,
                    Longitude = endereco.Longitude.Value,
                    Raio = regraLoja.RaioBusca
                });
                e.Lojas = await regraLoja.buscar(endereco.Latitude.Value, endereco.Longitude.Value, regraLoja.RaioBusca, seguimento.Id);
            };
            */
            return lojaListaPage;
        }

        /*
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
                        UserDialogs.Instance.HideLoading();
                        await regraLoja.gravarAtual(lojas[0]);
                        if (App.Current.MainPage is RootPage) {
                            ((RootPage)App.Current.MainPage).atualizarMenu();
                        }
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
        */

        /*

        [Obsolete]
        public static Page gerarLoginOld(bool usaRoot = true) {
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
        */
    }
}
