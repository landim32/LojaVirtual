using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Base.BLL;
using Emagine.Base.Estilo;
using Emagine.Base.Model;
using Emagine.Base.Pages;
using Emagine.Base.Utils;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Endereco.Utils;
using Emagine.GPS.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Utils;
using Emagine.Produto.Cells;
using Emagine.Produto.Factory;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using Loja.Cells;
using Loja.Pages;
using Xamarin.Forms;

namespace Emagine
{
    public class App : Application
    {
        public App()
        {
            GlobalUtils.URLAplicacao = "http://imarketsupermercados.com.br";
            //GlobalUtils.URLAplicacao = "http://emagine.com.br/digital";

            UsuarioFactory.Tipo = "Mobile";
            LojaFactory.Tipo = "Mobile";

            ProdutoUtils.ListaAbreJanela = true;
            ProdutoUtils.ListaItemTemplate = typeof(CustomProdutoCell);

            //GPSUtils.Current.TempoMinimo = 10;
            //GPSUtils.Current.DistanciaMinima = 30;
            GPSUtils.UsaLocalizacao = false;

            var estilo = criarEstilo();

            MainPage = new NavigationPage(new BlankPage());
            verificarLoja();
        }

        private static async void verificarLoja() {
            var lojaPage = await LojaUtils.gerarLoja(false);
            lojaPage.Appearing += (sender, e) => {
                PermissaoUtils.pedirPermissao();
            };
            if (lojaPage is ProdutoListaPage)
            {
                var listaProdutoPage = (ProdutoListaPage)lojaPage;
                //listaProdutoPage.AbreJanela = true;
                //listaProdutoPage.setItemTemplate(typeof(ProdutoBaseCell));
                App.Current.MainPage = gerarRootPage(listaProdutoPage);
            }
            else {
                App.Current.MainPage = new NavigationPage(lojaPage);
            }
        }

        public static Page gerarRootPage(Page mainPage) {
            var rootPage = new RootPage
            {
                NomeApp = "IMarket Supermercados Delivery",
                PaginaAtual = mainPage,
                Menus = gerarMenu()
            };
            rootPage.AoAtualizarMenu += (sender, e) =>
            {
                ((RootPage)sender).Menus = gerarMenu();
            };
            return rootPage;
        }

        private Estilo criarEstilo() {
            var estilo = Estilo.Current;
            estilo.PrimaryColor = Color.FromHex("#d84315");
            estilo.SuccessColor = Color.FromHex("#00c851");
            estilo.InfoColor = estilo.PrimaryColor;
            estilo.WarningColor = Color.FromHex("#f80");
            estilo.DangerColor = Color.FromHex("#d9534f");
            estilo.DefaultColor = Color.FromHex("#33b5e5");
            estilo.BarTitleColor = Color.White;
            estilo.BarBackgroundColor = estilo.PrimaryColor;//Color.FromHex("#221f1e");


            estilo.TelaPadrao = new EstiloPage
            {
                BackgroundColor = Color.FromHex("#d9d9d9")
                //BackgroundImage = "fundo.jpg"
            };
            estilo.TelaEmBranco = new EstiloPage
            {
                BackgroundColor = Color.White
                //BackgroundImage = "fundo.jpg"
            };
            estilo.BgPadrao.BackgroundColor = Color.FromHex("#ffffff");
            estilo.BgRoot = new EstiloStackLayout
            {
                BackgroundColor = estilo.TelaPadrao.BackgroundColor
            };
            estilo.BotaoRoot = new EstiloMenuButton
            {
                FontFamily = estilo.FontDefaultRegular,
                BackgroundColor = Color.White,
                FontSize = 18
            };
            estilo.BgInvestido = new EstiloStackLayout
            {
                BackgroundColor = estilo.PrimaryColor
            };
            estilo.MenuPagina = new EstiloPage
            {
                BackgroundColor = estilo.PrimaryColor //estilo.BarBackgroundColor
            };
            estilo.MenuTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.White, // estilo.PrimaryColor,
                FontSize = 18
            };
            estilo.MenuLista = new EstiloListView
            {
                SeparatorColor = estilo.MenuTexto.TextColor
            };
            estilo.MenuIcone = new EstiloIcon
            {
                IconColor = estilo.MenuTexto.TextColor,
                IconSize = 22
            };
            estilo.EntryPadrao = new EstiloEntry
            {
                FontFamily = estilo.FontDefaultRegular
            };
            estilo.SearchBar = new EstiloSearch
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 18,
                FontAttributes = FontAttributes.Bold
            };
            estilo.BotaoPrincipal = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultBold,
                BackgroundColor = estilo.PrimaryColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.BotaoSucesso = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultBold,
                BackgroundColor = estilo.SuccessColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.BotaoInfo = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultRegular,
                BackgroundColor = estilo.InfoColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.BotaoPadrao = new EstiloBotao
            {
                FontFamily = estilo.FontDefaultRegular,
                BackgroundColor = estilo.DefaultColor,
                TextColor = Color.White,
                CornerRadius = 10
            };
            estilo.Titulo1 = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                TextColor = Color.Black,
                FontAttributes = FontAttributes.Bold,
                FontSize = 24
            };
            estilo.Titulo2 = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                TextColor = Color.Black,
                FontAttributes = FontAttributes.Bold,
                FontSize = 20
            };
            estilo.Titulo3 = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                TextColor = Color.Black,
                FontAttributes = FontAttributes.Bold,
                FontSize = 16
            };
            estilo.LabelControle = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.FromHex("#7c7c7c"),
                FontSize = 12
            };
            estilo.LabelCampo = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.Black,
                FontSize = 16,
                FontAttributes = FontAttributes.Bold
            };
            estilo.ListaItem = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 24,
                FontAttributes = FontAttributes.Bold
            };
            estilo.ListaBadgeTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultItalic,
                FontSize = 11,
                TextColor = estilo.BarTitleColor
            };
            estilo.ListaBadgeFundo = new EstiloFrame
            {
                WidthRequest = 60,
                HorizontalOptions = LayoutOptions.End,
                VerticalOptions = LayoutOptions.Center,
                CornerRadius = 10,
                BackgroundColor = estilo.BarBackgroundColor,
                Padding = new Thickness(4, 3),
            };
            estilo.IconePadrao = new EstiloIcon
            {
                IconSize = 20
            };
            estilo.TotalFrame = new EstiloFrame
            {
                Margin = new Thickness(4, 0, 4, 5),
                Padding = new Thickness(3, 2),
                CornerRadius = 10,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                BackgroundColor = estilo.BarBackgroundColor
            };
            estilo.TotalLabel = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                FontSize = 11,
                TextColor = estilo.BarTitleColor
            };
            estilo.TotalTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 20,
                TextColor = estilo.BarTitleColor
            };
            estilo.EnderecoItem = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 20,
                FontAttributes = FontAttributes.Bold
            };
            estilo.EnderecoFrame = new EstiloFrame
            {
                CornerRadius = 5,
                Padding = 2,
                Margin = new Thickness(2, 2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
            };
            estilo.EnderecoTitulo = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 16,
                FontAttributes = FontAttributes.Bold
            };
            estilo.EnderecoCampo = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 14,
                FontAttributes = FontAttributes.Bold
            };
            estilo.EnderecoLabel = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                FontSize = 12
            };

            App.Current.Resources = estilo.gerar();
            return estilo;
        }

        public static IList<MenuItemInfo> gerarMenu() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            bool estaLogado = usuario != null && usuario.Id > 0;

            var menus = new List<MenuItemInfo>();

            var regraLoja = LojaFactory.create();
            if (regraLoja.podeMudarLoja())
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-home",
                    Titulo = "Lojas",
                    aoClicar = (sender, e) =>
                    {
                        var lojaPage = LojaUtils.gerarSelecionar();
                        ((RootPage)Current.MainPage).PaginaAtual = lojaPage;
                    }
                });
            }

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-search",
                Titulo = "Buscar",
                aoClicar = (sender, e) =>
                {
                    var buscaPage = ProdutoUtils.gerarProdutoBusca();
                    //buscaPage.AbreJanela = true;
                    //buscaPage.setItemTemplate(typeof(ProdutoBaseCell));
                    ((RootPage)Current.MainPage).PaginaAtual = buscaPage;
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-bars",
                Titulo = "Departamentos",
                aoClicar = (sender, e) =>
                {
                    var categoriaPage = new CategoriaListaPage {
                        Title = "Departamentos"
                    };
                    categoriaPage.AoAbrirProdutoLista += (s2, promocaoListaPage) => {
                        //promocaoListaPage.AbreJanela = true;
                        //promocaoListaPage.setItemTemplate(typeof(ProdutoBaseCell));
                        ((Page)s2).Navigation.PushAsync(promocaoListaPage);
                    };
                    ((RootPage)Current.MainPage).PaginaAtual = categoriaPage;
                }
            });

            /*
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-dollar",
                Titulo = "Em destaque",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)Current.MainPage).PaginaAtual = ProdutoUtils.gerarProdutoListaDestaque();
                }
            });
            */

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-star",
                Titulo = "Em promoção",
                aoClicar = (sender, e) =>
                {
                    var promocaoListaPage = ProdutoUtils.gerarProdutoListaPromocao();
                    //promocaoListaPage.AbreJanela = true;
                    //promocaoListaPage.setItemTemplate(typeof(ProdutoBaseCell));
                    ((RootPage)Current.MainPage).PaginaAtual = promocaoListaPage;
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-shopping-cart",
                Titulo = "Meu Carrinho",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)Current.MainPage).PushAsync(CarrinhoUtils.gerarCarrinhoParaEntrega());
                }
            });

            if (!estaLogado)
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Entrar",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)Current.MainPage).PushAsync(LoginUtils.gerarLogin());
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user-plus",
                    Titulo = "Criar Conta",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)Current.MainPage).PaginaAtual = LoginUtils.gerarCadastro();
                    }
                });
            }
            else {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Alterar Conta",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)Current.MainPage).PaginaAtual = new UsuarioGerenciaPage();
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-shopping-bag",
                    Titulo = "Meus Pedidos",
                    aoClicar = async (sender, e) =>
                    {
                        await PedidoUtils.gerarMeuPedido();
                    }
                });
            }

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-comment",
                Titulo = "Fale Conosco",
                aoClicar = (sender, e) =>
                {
                    //Device.OpenUri(new Uri("mailto:rodrigo@emagine.com.br"));
                    Device.OpenUri(new Uri("mailto:contato@imarketsupermercados.com.br"));
                }
            });

            /*
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-question",
                Titulo = "Sobre o App",
                aoClicar = (sender, e) =>
                {

                }
            });
            */

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-remove",
                Titulo = "Sair",
                aoClicar = async (sender, e) =>
                {
                    var regraCarrinho = CarrinhoFactory.create();
                    regraCarrinho.limpar();
                    var regraLogin = UsuarioFactory.create();
                    await LojaFactory.create().limparAtual();
                    await regraLogin.limparAtual();
                    App.verificarLoja();
                    //Current.MainPage = new NavigationPage(App.gerarBuscaCep());
                }
            });

            return menus;
        }

        protected override void OnStart()
        {
            // Handle when your app starts
        }

        protected override void OnSleep()
        {
            // Handle when your app sleeps
        }

        protected override void OnResume()
        {
            // Handle when your app resumes
        }
    }
}
