using System;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Emagine.Base.Estilo;

namespace Emagine.Frete.Pages
{
    public class PagamentoPage : ContentPage
    {
        private Label _PesoLabel;
        private Label _LarguraLabel;
        private Label _AlturaLabel;
        private Label _ProfundidadeLabel;
        private Label _FornecedorNomeLabel;
        private Label _FornecedorTelefoneLabel;
        private Label _OrigemLabel;
        private Label _DestinoLabel;
        private Label _DistanciaLabel;
        private Image _FotoFornecedorImage;
        private Image _FotoCarroImage;
        private Label _ValorFreteLabel;

        public PagamentoPage()
        {
            Title = "Efetuar Pagamento";
            inicializarComponente();

            var grid = new Grid
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(10, 0)
            };
            grid.Children.Add(_FotoFornecedorImage, 0, 0);
            grid.Children.Add(_FotoCarroImage, 1, 0);

            Content = new ScrollView {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout()
                {
                    Padding = new Thickness(10, 20),
                    Spacing = 5,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    Orientation = StackOrientation.Vertical,
                    Children = {
                        new Image{
                            Source = "logo.png",
                            HeightRequest = 120,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Center
                        },
                        gerarFornecedor(),
                        gerarDestino(),
                        grid,
                        gerarPainelProduto(),
                        gerarValorFrete()
                    }
                }
            };
        }

        private StackLayout gerarFornecedor() {
            return new StackLayout { 
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 5),
                Padding = 5,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                WidthRequest = 100,
                                TextColor = Estilo.Current.PrimaryColor,
                                Text = "Fornecedor:",
                                HorizontalTextAlignment = TextAlignment.End
                            },
                            _FornecedorNomeLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                WidthRequest = 100,
                                TextColor = Estilo.Current.PrimaryColor,
                                Text = "Telefone:",
                                HorizontalTextAlignment = TextAlignment.End
                            },
                            _FornecedorTelefoneLabel
                        }
                    },
                }
            };
        }

        private StackLayout gerarDestino()
        {
            return new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 5),
                Padding = 5,
                Children = {
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                WidthRequest = 100,
                                TextColor = Estilo.Current.PrimaryColor,
                                Text = "Origem:",
                                HorizontalTextAlignment = TextAlignment.End
                            },
                            _OrigemLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                WidthRequest = 100,
                                TextColor = Estilo.Current.PrimaryColor,
                                Text = "Destino:",
                                HorizontalTextAlignment = TextAlignment.End
                            },
                            _DestinoLabel
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Start,
                                WidthRequest = 100,
                                TextColor = Estilo.Current.PrimaryColor,
                                Text = "Distância:",
                                HorizontalTextAlignment = TextAlignment.End
                            },
                            _DistanciaLabel 
                        }
                    },
                }
            };
        }

        private StackLayout gerarPainelProduto()
        {
            return new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 5),
                Padding = 5,
                BackgroundColor = Estilo.Current.PrimaryColor,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Center,
                        VerticalTextAlignment = TextAlignment.Center,
                        Text = "Produto(s)",
                        TextColor = Color.White,
                        FontSize = 16
                    },
                    new BoxView {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        BackgroundColor = Color.White,
                        HeightRequest = 1
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                VerticalOptions = LayoutOptions.Center,
                                Text = "Peso",
                                TextColor = Color.White,
                                FontSize = 16
                            },
                            _PesoLabel
                        }
                    },
                    new BoxView {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        BackgroundColor = Color.White,
                        HeightRequest = 1
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                VerticalOptions = LayoutOptions.Center,
                                Text = "Largura",
                                TextColor = Color.White,
                                FontSize = 16
                            },
                            _LarguraLabel
                        }
                    },
                    new BoxView {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        BackgroundColor = Color.White,
                        HeightRequest = 1
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                VerticalOptions = LayoutOptions.Center,
                                Text = "Altura",
                                TextColor = Color.White,
                                FontSize = 16
                            },
                            _AlturaLabel
                        }
                    },
                    new BoxView {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        BackgroundColor = Color.White,
                        HeightRequest = 1
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Spacing = 5,
                        Children = {
                            new Label {
                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                VerticalOptions = LayoutOptions.Center,
                                Text = "Profundidade",
                                TextColor = Color.White,
                                FontSize = 16
                            },
                            _ProfundidadeLabel
                        }
                    }
                }
            };
        }

        private StackLayout gerarValorButton() {
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += (sender, e) => {
                // Rodrigo Landim - 16/03
                //Navigation.PushAsync(new CartaoPage(new Model.FreteInfo()));
            };
            var tituloLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                TextColor = Color.White,
                FontSize = 10,
                Text = "VALOR DO FRETE / ACEITAR"
            };
            tituloLabel.GestureRecognizers.Add(tapGestureRecognizer);
            _ValorFreteLabel.GestureRecognizers.Add(tapGestureRecognizer);

            var mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                BackgroundColor = Estilo.Current.PrimaryColor,
                Padding = 3,
                Children = {
                    tituloLabel,
                    _ValorFreteLabel
                }
            };

            mainLayout.GestureRecognizers.Add(tapGestureRecognizer);
            return mainLayout;
        }

        private StackLayout gerarRecusarButton() {
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += (sender, e) => {
                Navigation.PopAsync();
            };

            var tituloLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                HorizontalTextAlignment = TextAlignment.Center,
                VerticalTextAlignment = TextAlignment.Center,
                TextColor = Color.White,
                Text = "Recusar"
            };
            tituloLabel.GestureRecognizers.Add(tapGestureRecognizer);

            var mainLayout = new StackLayout { 
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Fill,
                WidthRequest = 100,
                BackgroundColor = Estilo.Current.SuccessColor,
                Padding = 3,
                Children = {
                    tituloLabel
                }
            };
            mainLayout.GestureRecognizers.Add(tapGestureRecognizer);

            return mainLayout;
        }

        private StackLayout gerarValorFrete() {
            return new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Spacing = 2,
                Margin = new Thickness(10, 0),
                Children = {
                    gerarValorButton(),
                    gerarRecusarButton()
                }
            };
        }


        private void inicializarComponente() {
            _FornecedorNomeLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                TextColor = Estilo.Current.PrimaryColor,
                Text = "Rodrigo Landim",
                FontAttributes = FontAttributes.Bold
            };
            _FornecedorTelefoneLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,

                TextColor = Estilo.Current.PrimaryColor,
                Text = "(62) 9 96257590",
                FontAttributes = FontAttributes.Bold
            };
            _OrigemLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                TextColor = Estilo.Current.PrimaryColor,
                Text = "Endereço de Origem",
                FontAttributes = FontAttributes.Bold
            };
            _DistanciaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                TextColor = Estilo.Current.PrimaryColor,
                Text = "30 km",
                FontAttributes = FontAttributes.Bold
            };
            _DestinoLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                TextColor = Estilo.Current.PrimaryColor,
                Text = "Endereço de Destino",
                FontAttributes = FontAttributes.Bold
            };
            _PesoLabel = new Label
            {
                WidthRequest = 120,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 16,
                HorizontalTextAlignment = TextAlignment.End,
                Text = "0,00"
            };
            _LarguraLabel = new Label
            {
                WidthRequest = 120,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 16,
                HorizontalTextAlignment = TextAlignment.End,
                Text = "0,00"
            };
            _AlturaLabel = new Label
            {
                WidthRequest = 120,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 16,
                HorizontalTextAlignment = TextAlignment.End,
                Text = "0,00"
            };
            _ProfundidadeLabel = new Label
            {
                WidthRequest = 120,
                BackgroundColor = Estilo.Current.PrimaryColor,
                TextColor = Color.White,
                FontSize = 16,
                HorizontalTextAlignment = TextAlignment.End,
                Text = "0,00"
            };
            _FotoFornecedorImage = new Image{
                Source = "btnRastrear.png",
                Aspect = Aspect.AspectFit
            };
            _FotoCarroImage = new Image
            {
                Source = "btnRastrear.png",
                Aspect = Aspect.AspectFit
            };
            _ValorFreteLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.CenterAndExpand,
                TextColor = Color.White,
                Text = "R$ 150,00",
                FontSize = 26,
                FontAttributes = FontAttributes.Bold
            };
        }
    }
}

