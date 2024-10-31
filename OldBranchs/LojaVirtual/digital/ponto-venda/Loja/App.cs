using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Banner.Utils;
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
using Emagine.Login.Utils;
using Emagine.Mapa.Model;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Model;
using Emagine.Pedido.Pages;
using Emagine.Pedido.Utils;
using Emagine.Produto.Cells;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine
{
    public class App : Application
    {
        public App()
        {
            GlobalUtils.URLAplicacao = "http://emagine.com.br/loja-demo";
            UsuarioFactory.Tipo = "Mobile";
            LojaFactory.Tipo = "Mobile";
            ProdutoUtils.ListaAbreJanela = true;
            ProdutoUtils.ListaItemTemplate = typeof(ProdutoCarrinhoCell);
            ProdutoUtils.CarrinhoItemTemplate = typeof(ProdutoCarrinhoCell);
            ProdutoListaPageFactory.Tipo = typeof(ProdutoGridPage);
            //CategoriaPageFactory.Tipo = typeof(CategoriaGridPage);
            CategoriaPageFactory.Tipo = typeof(CategoriaListaPage);

            //GPSUtils.Current.TempoMinimo = 10;
            //GPSUtils.Current.DistanciaMinima = 30;
            GPSUtils.UsaLocalizacao = false;
            BannerUtils.Ativo = false;

            var estilo = criarEstilo();
            inicilizarApp();
        }

        /*
        private async Task produtoAoCarregar(object sender, ProdutoListaEventArgs args)
        {
            var regraProduto = ProdutoFactory.create();
            var produtos = new List<ProdutoInfo>();
            int i = 0;
            foreach (var produto in await regraProduto.listar(15)) {
                produtos.Add(produto);
                i++;
                if (i >= 10) {
                    break;
                }
            }
            args.Produtos = produtos;
            return;
        }
        */

        private static void inicilizarApp()
        {
            var regraLoja = LojaFactory.create();
            regraLoja.RaioBusca = 10000;
            var blankPage = new BlankPage();
            blankPage.Appearing += (sender, e) => {
                PermissaoUtils.pedirPermissao();
            };
            App.Current.MainPage = gerarRootPage(blankPage);
            LojaUtils.inicializarLojaLista();
        }

        /*
        private static async void verificarLoja() {
            var lojaPage = await LojaUtils.gerarLoja();
            lojaPage.Appearing += (sender, e) => {
                PermissaoUtils.pedirPermissao();
            };
            if (lojaPage is ProdutoListaPage)
            {
                App.Current.MainPage = gerarRootPage(lojaPage);
            }
            else {
                App.Current.MainPage = new NavigationPage(lojaPage) {
                    BarBackgroundColor = Estilo.Current.BarBackgroundColor,
                    BarTextColor = Estilo.Current.BarTitleColor
                };
            }
        }
        */

        public static Page gerarRootPage(Page mainPage) {
            var rootPage = new RootPage
            {
                NomeApp = "Emagine Loja",
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
            estilo.PrimaryColor = Color.FromHex("#1da9df");
            estilo.SuccessColor = Color.FromHex("#5cb85c");
            estilo.InfoColor = Color.FromHex("#5bc0de");
            estilo.WarningColor = Color.FromHex("#f0ad4e");
            estilo.DangerColor = Color.FromHex("#d9534f");
            estilo.DefaultColor = Color.Gray;
            estilo.BarTitleColor = Color.FromHex("#ffffff");
            estilo.BarBackgroundColor = Color.FromHex("#197da6");

            switch (Device.RuntimePlatform) {
                case Device.iOS:
                    estilo.FontDefaultRegular = "Raleway-Regular.ttf";
                    estilo.FontDefaultBold = "Raleway-Bold.ttf";
                    estilo.FontDefaultItalic = "Raleway-Italic.ttf";
                    break;
                case Device.Android:
                    estilo.FontDefaultRegular = "Raleway-Regular.ttf#Raleway-Regular";
                    estilo.FontDefaultBold = "Raleway-Bold.ttf#Raleway-Bold";
                    estilo.FontDefaultItalic = "Raleway-Italic.ttf#Raleway-Italic";
                    break;
                case Device.WinPhone:
                    estilo.FontDefaultRegular = "Raleway-Regular.ttf";
                    estilo.FontDefaultBold = "Raleway-Bold.ttf";
                    estilo.FontDefaultItalic = "Raleway-Italic.ttf";
                    break;
            }
            estilo.TelaPadrao = new EstiloPage {
                BackgroundColor = Color.FromHex("#d9d9d9")
            };
            estilo.TelaEmBranco = new EstiloPage
            {
                BackgroundColor = Color.White
            };
            estilo.BgPadrao.BackgroundColor = Color.FromHex("#ffffff");
            estilo.BgRoot = new EstiloStackLayout {
                BackgroundColor = estilo.TelaPadrao.BackgroundColor
            };
            estilo.BotaoRoot = new EstiloMenuButton {
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
                //TextColor = Color.FromHex("#ffc500"),
                TextColor = Color.White,
                FontSize = 18
            };
            estilo.MenuLista = new EstiloListView
            {
                SeparatorColor = estilo.MenuTexto.TextColor
            };
            estilo.MenuIcone = new EstiloIcon {
                IconColor = estilo.MenuTexto.TextColor,
                IconSize = 22
            };
            estilo.EntryPadrao = new EstiloEntry {
                FontFamily = estilo.FontDefaultRegular
            };
            estilo.SearchBar = new EstiloSearch
            {
                FontFamily = estilo.FontDefaultBold,
                FontSize = 18,
                FontAttributes = FontAttributes.Bold
            };
            estilo.BotaoPrincipal = new EstiloBotao {
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
            estilo.Titulo1 = new EstiloLabel {
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
            estilo.ListaFramePadrao = new EstiloFrame
            {
                Margin = new Thickness(4, 0, 4, 6),
                Padding = new Thickness(6, 4),
                CornerRadius = 10,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                BackgroundColor = Color.White
            };
            estilo.ListaItem = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultBold,
                //FontSize = 24,
                FontSize = 18,
                FontAttributes = FontAttributes.Bold
            };
            estilo.ListaBadgeTexto = new EstiloLabel
            {
                FontFamily = estilo.FontDefaultItalic,
                FontSize = 11,
                TextColor = estilo.BarTitleColor
            };
            estilo.ListaBadgeFundo = new EstiloFrame {
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

            estilo.Total = new EstiloTotal
            {
                Frame = new EstiloFrame
                {
                    Margin = new Thickness(4, 0, 4, 5),
                    Padding = new Thickness(3, 10),
                    CornerRadius = 15,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    BackgroundColor = estilo.SuccessColor
                    //BackgroundColor = Color.FromHex("#bced8c"),
                },
                Label = new EstiloLabel
                {
                    FontFamily = estilo.FontDefaultRegular,
                    FontSize = 12,
                    //TextColor = estilo.BarTitleColor
                    //TextColor = estilo.BarTitleColor,
                    //TextColor = Color.Black,
                    TextColor = Color.Black,
                },
                Texto = new EstiloLabel
                {
                    FontFamily = estilo.FontDefaultBold,
                    FontAttributes = FontAttributes.Bold,
                    FontSize = 14,
                    //TextColor = estilo.BarTitleColor
                    TextColor = Color.Black,
                }
            };

            estilo.Produto = new EstiloProduto
            {
                Frame = new EstiloFrame
                {
                    CornerRadius = 7,
                    Padding = 2,
                    //Margin = new Thickness(2, 2),
                    //BackgroundColor = Color.FromHex("#feecd6")
                    BackgroundColor = Color.White
                },
                Foto = new EstiloImage
                {
                    WidthRequest = 80,
                    HeightRequest = 110,
                    Aspect = Aspect.AspectFit
                },
                Titulo = new EstiloLabel
                {
                    FontFamily = Estilo.Current.FontDefaultBold,
                    //FontSize = 20,
                    FontSize = 12,
                    FontAttributes = FontAttributes.Bold,
                    //LineBreakMode = LineBreakMode.TailTruncation,
                    //TextColor = Estilo.Current.PrimaryColor
                    TextColor = estilo.BarBackgroundColor
                },
                Descricao = new EstiloLabel
                {
                    FontAttributes = FontAttributes.Bold,
                    TextColor = Color.FromHex("#777777")
                },
                Volume = new EstiloLabel
                {
                    FontAttributes = FontAttributes.Bold,
                    TextColor = Color.FromHex("#777777")
                },
                Label = new EstiloLabel
                {
                    FontAttributes = FontAttributes.None,
                    FontSize = 9
                },
                Quantidade = new EstiloLabel
                {
                    FontAttributes = FontAttributes.Bold,
                    TextColor = Color.FromHex("#ff0000"),
                    FontSize = 10
                },
                PrecoMoeda = new EstiloLabel
                {
                    //FontSize = 11
                    FontSize = 7
                },
                PrecoValor = new EstiloLabel
                {
                    FontFamily = Estilo.Current.FontDefaultBold,
                    FontAttributes = FontAttributes.Bold,
                    FontSize = 12
                    //FontSize = 24
                },
                PromocaoMoeda = new EstiloLabel
                {
                    //FontSize = 11
                    FontSize = 7,
                    //TextColor = Color.FromHex("#ff0000"),
                },
                PromocaoValor = new EstiloLabel
                {
                    FontFamily = Estilo.Current.FontDefaultBold,
                    FontAttributes = FontAttributes.Bold,
                    FontSize = 12,
                    //TextColor = Color.FromHex("#ff0000"),
                    //FontSize = 24
                },
                Icone = new EstiloIcon
                {
                    IconColor = Color.FromHex("#ffc500"),
                    IconSize = 22
                    //IconSize = 24
                },
                Carrinho = new EstiloBotao
                {
                    FontFamily = estilo.FontDefaultBold,
                    FontAttributes = FontAttributes.Bold,
                    BackgroundColor = estilo.SuccessColor,
                    TextColor = Color.Black,
                    CornerRadius = 15,
                    FontSize = 14,
                    //BorderWidth = 1,
                    //BorderColor = Color.FromHex("#7a7a7a")
                }
            };

            estilo.Loja = new EstiloLoja
            {
                Frame = new EstiloFrame
                {
                    CornerRadius = 8,
                    Padding = 3,
                    Margin = new Thickness(5, 2, 5, 3),
                    BackgroundColor = Color.White
                },
                Foto = new EstiloImage
                {
                    Aspect = Aspect.AspectFit,
                    WidthRequest = 80,
                    HeightRequest = 80
                },
                Titulo = new EstiloLabel {
                    FontFamily = Estilo.Current.FontDefaultBold,
                    FontAttributes = FontAttributes.Bold,
                    TextColor = Estilo.Current.BarBackgroundColor,
                    FontSize = 18,
                },
                Endereco = new EstiloLabel {
                    FontFamily = Estilo.Current.FontDefaultItalic,
                    FontAttributes = FontAttributes.Italic,
                    TextColor = Color.FromHex("#7c7c7c"),
                    FontSize = 12,
                },
                Distancia = new EstiloLabel
                {
                    FontFamily = Estilo.Current.FontDefaultBold,
                    FontAttributes = FontAttributes.Bold,
                    TextColor = estilo.SuccessColor, //Color.FromHex("#7c7c7c"),
                    FontSize = 14,
                },
                Icone = new EstiloIcon {
                    IconSize = 20
                }
            };

            estilo.Quantidade = new EstiloQuantidade
            {
                AdicionarBotao = new EstiloFrame
                {
                    BackgroundColor = estilo.SuccessColor,
                },
                AdicionarIcone = new EstiloIcon
                {
                    IconColor = Color.Black,
                    IconSize = 20,
                },
                RemoverBotao = new EstiloFrame
                {
                    BackgroundColor = estilo.DangerColor,
                },
                RemoverIcone = new EstiloIcon
                {
                    IconColor = Color.Black,
                    IconSize = 20,
                },
                Fundo = new EstiloFrame
                {
                    Padding = 5,
                    BackgroundColor = Color.Silver,
                },
                QuantidadeTexto = new EstiloLabel
                {
                    FontSize = 16,
                    FontAttributes = FontAttributes.Bold
                }
            };

            App.Current.Resources = estilo.gerar();
            return estilo;
        }

        public static IList<MenuItemInfo> gerarMenu() {
            var regraUsuario = UsuarioFactory.create();
            var regraLoja = LojaFactory.create();

            var loja = regraLoja.pegarAtual();

            var usuario = regraUsuario.pegarAtual();
            bool estaLogado = usuario != null && usuario.Id > 0;

            var menus = new List<MenuItemInfo>();

            //if (regraLoja.podeMudarLoja())
            //{
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-home",
                Titulo = "Lojas",
                aoClicar = (sender, e) =>
                {
                    LojaUtils.inicializarLojaLista();
                    //((RootPage)Current.MainPage).PaginaAtual = lojaPage;
                }
            });
            /*
            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-home",
                Titulo = "Seguimentos",
                aoClicar = async (sender, e) =>
                {
                    var telaInicialPage = await LojaUtils.gerarTelaInicial();
                    ((RootPage)Current.MainPage).PaginaAtual = telaInicialPage;
                }
            });
            */
            //}

            if (loja != null)
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-bars",
                    Titulo = "Categorias",
                    aoClicar = (sender, e) =>
                    {
                        var categoriaPage = CategoriaPageFactory.create();
                        categoriaPage.BannerVisivel = BannerUtils.Ativo;
                        categoriaPage.Title = "Categorias";
                        ((RootPage)Current.MainPage).PaginaAtual = categoriaPage;
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-shopping-bag",
                    Titulo = "Lista de Compras",
                    aoClicar = (sender, e) =>
                    {
                        var listaCompraPage = new ListaCompraPage {
                            Title = "Lista de Compras"
                        };
                        ((RootPage)Current.MainPage).PushAsync(listaCompraPage);
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-dollar",
                    Titulo = "Em promoção",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)Current.MainPage).PaginaAtual = ProdutoUtils.gerarProdutoListaPromocao();
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-search",
                    Titulo = "Buscar",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)Current.MainPage).PaginaAtual = ProdutoUtils.gerarProdutoBusca();
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
            }

            if (!estaLogado)
            {
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user",
                    Titulo = "Entrar",
                    aoClicar = (sender, e) =>
                    {
                        var loginPage = new LoginPage
                        {
                            Title = "Login"
                        };
                        loginPage.AoLogar += (s, u) =>
                        {
                            var destaquePage = ProdutoUtils.gerarProdutoListaDestaque();
                            ((RootPage)Current.MainPage).PaginaAtual = destaquePage;
                        };
                        ((RootPage)Current.MainPage).PushAsync(loginPage);
                    }
                });
                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-user-plus",
                    Titulo = "Criar Conta",
                    aoClicar = (sender, e) =>
                    {
                        ((RootPage)Current.MainPage).PaginaAtual = LoginUtils.gerarCadastro((u) => {
                            var destaquePage = ProdutoUtils.gerarProdutoListaDestaque();
                            ((RootPage)Current.MainPage).PaginaAtual = destaquePage;
                        });
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
                        ((RootPage)Current.MainPage).PaginaAtual = new PedidoUsuarioGerenciaPage();
                    }
                });

                menus.Add(new MenuItemInfo
                {
                    IconeFA = "fa-shopping-basket",
                    Titulo = "Meus Pedidos",
                    aoClicar = async (sender, e) =>
                    {
                        await PedidoUtils.gerarMeuPedido();
                    }
                });
            }

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-map-marker",
                Titulo = "Raio de Busca",
                aoClicar = (sender, e) =>
                {
                    var raioBuscaPage = new RaioBuscaPage
                    {
                        Title = "Mudar Raio de Busca",
                        BotaoTexto = "Gravar"
                    };
                    raioBuscaPage.AoAvancar += (s2, e2) => {
                        raioBuscaPage.DisplayAlert("Sucesso", "Raio alterado com sucesso.", "Entendi");
                    };
                    ((RootPage)Current.MainPage).PushAsync(raioBuscaPage);
                }
            });

            menus.Add(new MenuItemInfo
            {
                IconeFA = "fa-comment",
                Titulo = "Fale Conosco",
                aoClicar = (sender, e) =>
                {
                    Device.OpenUri(new Uri("mailto:rodrigo@emagine.com.br"));
                }
            });

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
                    App.inicilizarApp();
                    //App.verificarSeguimento();
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
