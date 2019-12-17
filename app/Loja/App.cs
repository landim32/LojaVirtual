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
using Emagine.Produto.Factory;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using Loja.Cells;
using Loja.Pages;
using Loja.Utils;
using Xamarin.Forms;

namespace Emagine
{
    public class App : Application
    {
        public App()
        {
            GlobalUtils.URLAplicacao = "http://emagine.com.br/pao-no-sinal";

            GPSUtils.UsaLocalizacao = true;
            GPSUtils.Current.TempoMinimo = 10;
            //GPSUtils.Current.DistanciaMinima = 30;
            GPSUtils.Current.DistanciaMinima = 0;

            UsuarioFactory.Tipo = "Mobile";
            LojaFactory.Tipo = "Mobile";
            EntregaMetodoPageFactory.Tipo = typeof(CustomEntregaMetodoPage);
            UsuarioFormPageFactory.Tipo = typeof(CustomUsuarioFormPage);
            ProdutoUtils.ListaItemTemplate = typeof(CustomProdutoCell);
            ProdutoUtils.CarrinhoItemTemplate = typeof(CustomProdutoCell);

            var estilo = criarEstilo();

            MainPage = new NavigationPage(new BlankPage());
            //verificarLoja();
            //var regraUsuario = UsuarioFactory.create();
            //var usuario = regraUsuario.pegarAtual();
            //inicializarLoja();

            GeralUtils.inicializar();

            /*
            var termoConcordanciaPage = new DocumentoPage
            {
                Title = "TERMO DE CONCORDÂNCIA",
                //NomeArquivo = "termo_concordancia.html"
            };
            termoConcordanciaPage.AoConfirmar += (s2, e2) => {
                //base.CadastroClicked(sender, e);
            };
            termoConcordanciaPage.AoNegar += (s3, e3) => {
                ((Page)s3).Navigation.PopAsync();
            };
            //Navigation.PushAsync(termoConcordanciaPage);
            App.Current.MainPage = new NavigationPage(termoConcordanciaPage);
            */
        }

        /*
        public static void inicializarLoja() {
            var introPage = new IntoPage();
            introPage.AoAvancar += (sender, e) => {
                GeralUtils.inicializarLoja(async (usuario) => {
                    var lojaPage = await LojaUtils.gerarLoja();
                    lojaPage.Appearing += (s3, e3) => {
                        PermissaoUtils.pedirPermissao();
                    };
                    if (lojaPage is ProdutoListaPage) {
                        App.Current.MainPage = gerarRootPage(lojaPage);
                    }
                    else {
                        App.Current.MainPage = new NavigationPage(lojaPage);
                    }
                });
            };
            App.Current.MainPage = new NavigationPage(introPage);
        }
        */

        /*
        public static void introducaoAoAvancar(object sender, EventArgs e) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario == null) {
            }

            var cadastroPage = EnderecoUtils.gerarBuscaPorCep(async (endereco) => {
                var lojaPage = await LojaUtils.gerarLoja();
                lojaPage.Appearing += (s3, e3) => {
                    PermissaoUtils.pedirPermissao();
                };
                if (lojaPage is ProdutoListaPage) {
                    App.Current.MainPage = gerarRootPage(lojaPage);
                }
                else {
                    App.Current.MainPage = new NavigationPage(lojaPage);
                }
            }, true);
            App.Current.MainPage = new NavigationPage(cadastroPage);
            */

            /*
            var termoConcordanciaPage = new DocumentoPage {
                Title = "TERMO DE CONCORDÂNCIA",
                NomeArquivo = "termo_concordancia.html"
            };
            termoConcordanciaPage.AoConfirmar += async (s2, e2) => {

            };
            termoConcordanciaPage.AoNegar += (s3, e3) => {
                ((Page)s3).Navigation.PopAsync();
            };
            ((Page)App.Current.MainPage).Navigation.PushAsync(termoConcordanciaPage);
            //((Page)sender).Navigation.PushAsync(termoConcordanciaPage);
            */
        //}

        /*
        private static void verificarLoja() {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja == null) {
                var introPage = new IntoPage();
                introPage.AoAvancar += introducaoAoAvancar;
                App.Current.MainPage = new NavigationPage(introPage);
            }
            else {
                introducaoAoAvancar(App.Current.MainPage, new EventArgs());
            }
        }
        */

        public static Page gerarRootPage(Page mainPage) {
            var rootPage = new RootPage
            {
                NomeApp = "Pão no Sinal",
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
            estilo.PrimaryColor = Color.FromHex("#ffc500");
            //estilo.PrimaryColor = Color.FromHex("#412e04");
            estilo.SuccessColor = Color.FromHex("#00c851");
            //estilo.InfoColor = estilo.PrimaryColor;
            estilo.InfoColor = Color.FromHex("#412e04");
            estilo.WarningColor = Color.FromHex("#f80");
            estilo.DangerColor = Color.FromHex("#d9534f");
            estilo.DefaultColor = Color.FromHex("#33b5e5");
            estilo.BarTitleColor = Color.White;
            estilo.BarBackgroundColor = Color.FromHex("#2d2d30");
           
            estilo.TelaPadrao = new EstiloPage
            {
                BackgroundColor = Color.FromHex("#d9d9d9"),
                BackgroundImage = "fundo.jpg"
            };
            estilo.TelaEmBranco = new EstiloPage
            {
                BackgroundColor = Color.White,
                BackgroundImage = "fundo.jpg"
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
                BackgroundColor = estilo.BarBackgroundColor
            };
            estilo.MenuTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultRegular,
                TextColor = Color.FromHex("#ffc500"),
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

            estilo.Produto.Frame = new EstiloFrame
            {
                CornerRadius = 5,
                Padding = 2,
                Margin = new Thickness(2, 2),
                BackgroundColor = Color.FromHex("#feecd6")
            };
            estilo.Produto.Foto = new EstiloImage {
                WidthRequest = 80,
                HeightRequest = 110,
                Aspect = Aspect.AspectFit
            };
            estilo.Produto.Titulo = new EstiloLabel
            {
                FontFamily = Estilo.Current.FontDefaultBold,
                FontSize = 20,
                //FontSize = 16,
                FontAttributes = FontAttributes.Bold,
                LineBreakMode = LineBreakMode.TailTruncation,
                TextColor = Estilo.Current.PrimaryColor
            };
            estilo.Produto.Descricao = new EstiloLabel {
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#777777")
            };
            estilo.Produto.Volume = new EstiloLabel
            {
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#777777")
            };
            estilo.Produto.Quantidade = new EstiloLabel
            {
                FontAttributes = FontAttributes.Bold,
                TextColor = Color.FromHex("#777777")
            };
            estilo.Produto.PrecoMoeda = new EstiloLabel
            {
                //FontSize = 11
                FontSize = 9
            };
            estilo.Produto.PrecoValor = new EstiloLabel
            {
                FontFamily = Estilo.Current.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 18
                //FontSize = 24
            };
            estilo.Produto.PromocaoMoeda = new EstiloLabel
            {
                //FontSize = 11
                FontSize = 9
            };
            estilo.Produto.PromocaoValor = new EstiloLabel
            {
                FontFamily = Estilo.Current.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 18
                //FontSize = 24
            };
            estilo.Produto.Icone = new EstiloIcon
            {
                IconColor = Color.FromHex("#ffc500"),
                IconSize = 22
                //IconSize = 24
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
                IconeFA = "fa-bars",
                Titulo = "Categorias",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)Current.MainPage).PaginaAtual = new CategoriaListaPage();
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-star",
                Titulo = "Em destaque",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)Current.MainPage).PaginaAtual = ProdutoUtils.gerarProdutoListaDestaque();
                }
            });

            /*
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-dollar",
                Titulo = "Em promoção",
                aoClicar = (sender, e) =>
                {
                    ((RootPage)Current.MainPage).PaginaAtual = ProdutoUtils.gerarProdutoListaPromocao();
                }
            });
            */

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
                    Titulo = "Minha Conta",
                    aoClicar = (sender, e) =>
                    {
                        //((RootPage)Current.MainPage).PaginaAtual = new UsuarioGerenciaPage();
                        ((RootPage)Current.MainPage).PushAsync(new UsuarioGerenciaPage());
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

            /*
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-comment",
                Titulo = "Fale Conosco",
                aoClicar = (sender, e) =>
                {
                    Device.OpenUri(new Uri("mailto:rodrigo@emagine.com.br"));
                }
            });
            */

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-question",
                Titulo = "Sobre o Pão no Sinal",
                aoClicar = async (sender, e) =>
                {
                    /*
                    var regraCarrinho = CarrinhoFactory.create();
                    regraCarrinho.limpar();
                    var regraLogin = UsuarioFactory.create();
                    await regraLoja.limparAtual();
                    await regraLogin.limparAtual();
                    */
                    /*
                    var sobrePage = new DocumentoPage
                    {
                        Title = "Sobre o Pão no Sinal",
                        NomeArquivo = "sobre.html",
                        SimVisivel = false,
                        NaoVisivel = false
                    };
                    sobrePage.AoNegar += async (s3, e3) => {
                        ((Page)s3).Navigation.PopAsync();
                    };
                    ((RootPage)Current.MainPage).PaginaAtual = sobrePage;
                    */
                    ((RootPage)Current.MainPage).PushAsync(GeralUtils.gerarSobre());
                    /*
                    if (await UserDialogs.Instance.ConfirmAsync("Tem certeza?", "Pergunta", "Sim", "Não")) {
                        ((RootPage)Current.MainPage).PushAsync(GeralUtils.gerarSobre());
                    }
                    */
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-ban",
                Titulo = "Políticas",
                aoClicar = (sender, e) =>
                {
                    var cancelamentoPage = new DocumentoPage
                    {
                        Title = "Políticas",
                        NomeArquivo = "cancelamento.html",
                        SimVisivel = false,
                        NaoVisivel = false
                    };
                    cancelamentoPage.AoNegar += (s3, e3) => {
                        ((Page)s3).Navigation.PopAsync();
                    };
                    ((RootPage)Current.MainPage).PaginaAtual = cancelamentoPage;
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-remove",
                Titulo = "Sair",
                aoClicar = async (sender, e) =>
                {
                    var regraCarrinho = CarrinhoFactory.create();
                    //var regraLoja = LojaFactory.create();
                    regraCarrinho.limpar();
                    var regraLogin = UsuarioFactory.create();
                    await regraLoja.limparAtual();
                    await regraLogin.limparAtual();
                    GeralUtils.inicializar();
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
