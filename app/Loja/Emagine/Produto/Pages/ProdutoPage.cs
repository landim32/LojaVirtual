using Emagine.Base.Estilo;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Produto.Controls;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using Plugin.Share;
using Plugin.Share.Abstractions;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class ProdutoPage : ContentPage
    {
        private const int LABEL_LARGURA = 80;
        private const int TAMANHO_FONTE = 10;

        private Image _fotoImage;
        private Label _categoriaLabel;
        private Label _codigoLabel;
        private Label _descricaoLabel;
        private Label _moedaValorLabel;
        private Label _valorFinalLabel;
        private Label _volumeLabel;
        private Label _quantidadeLabel;
        private AbsoluteLayout _promocaoStack;
        private Label _moedaPromocaoLabel;
        private Label _valorPromocaoLabel;
        private QuantidadeControl _quantidadeButton;
        private IconImage _destaqueIcon;
        //private Label _TotalLabel;
        private TotalCarrinhoView _totalView;

        public ProdutoInfo Produto {
            get {
                return (ProdutoInfo) BindingContext;
            }
            set {
                BindingContext = value;
            }
        }

        public ProdutoPage()
        {
            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Compartilhar",
                Icon = "fa-share-alt",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() => {
                    if (!CrossShare.IsSupported) {
                        return;
                    }
                    CrossShare.Current.Share(new ShareMessage
                    {
                        Title = Produto.Nome,
                        Url = Produto.Url
                    });
                })
            });

            inicializarComponente();

            Padding = new Thickness(3, 5);
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Children = {
                    new ScrollView {
                        Orientation = ScrollOrientation.Vertical,
                        VerticalOptions = LayoutOptions.FillAndExpand,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        Content = new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Fill,
                            Children = {
                                _fotoImage,
                                new Frame {
                                    Margin = 5,
                                    Style = Estilo.Current[Estilo.LISTA_FRAME_PADRAO],
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Fill,
                                    Content = new StackLayout {
                                        Orientation = StackOrientation.Horizontal,
                                        VerticalOptions = LayoutOptions.Start,
                                        HorizontalOptions = LayoutOptions.Fill,
                                        Children = {
                                            new StackLayout {
                                                Orientation = StackOrientation.Vertical,
                                                VerticalOptions = LayoutOptions.Start,
                                                HorizontalOptions = LayoutOptions.FillAndExpand,
                                                Spacing = 5,
                                                Children = {
                                                    new StackLayout {
                                                        Orientation = StackOrientation.Horizontal,
                                                        VerticalOptions = LayoutOptions.Start,
                                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                                        Spacing = 5,
                                                        Children = {
                                                            new Label {
                                                                VerticalOptions = LayoutOptions.CenterAndExpand,
                                                                HorizontalOptions = LayoutOptions.Start,
                                                                WidthRequest = LABEL_LARGURA,
                                                                HorizontalTextAlignment = TextAlignment.End,
                                                                VerticalTextAlignment = TextAlignment.Center,
                                                                LineBreakMode = LineBreakMode.TailTruncation,
                                                                FontSize = TAMANHO_FONTE,
                                                                Text = "Preço:"
                                                            },
                                                            _moedaValorLabel,
                                                            _valorFinalLabel,
                                                            _promocaoStack
                                                        }
                                                    },
                                                    new StackLayout {
                                                        Orientation = StackOrientation.Horizontal,
                                                        VerticalOptions = LayoutOptions.Start,
                                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                                        Spacing = 5,
                                                        Children = {
                                                            new Label {
                                                                VerticalOptions = LayoutOptions.CenterAndExpand,
                                                                HorizontalOptions = LayoutOptions.Start,
                                                                WidthRequest = LABEL_LARGURA,
                                                                HorizontalTextAlignment = TextAlignment.End,
                                                                LineBreakMode = LineBreakMode.TailTruncation,
                                                                FontSize = TAMANHO_FONTE,
                                                                Text = "Departamento:"
                                                            },
                                                            _categoriaLabel
                                                        }
                                                    },
                                                    new StackLayout {
                                                        Orientation = StackOrientation.Horizontal,
                                                        VerticalOptions = LayoutOptions.Start,
                                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                                        Spacing = 5,
                                                        Children = {
                                                            new Label {
                                                                VerticalOptions = LayoutOptions.CenterAndExpand,
                                                                HorizontalOptions = LayoutOptions.Start,
                                                                WidthRequest = LABEL_LARGURA,
                                                                HorizontalTextAlignment = TextAlignment.End,
                                                                LineBreakMode = LineBreakMode.TailTruncation,
                                                                FontSize = TAMANHO_FONTE,
                                                                Text = "Código:"
                                                            },
                                                            _codigoLabel
                                                        }
                                                    },
                                                    /*
                                                    new StackLayout {
                                                        Orientation = StackOrientation.Horizontal,
                                                        VerticalOptions = LayoutOptions.Start,
                                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                                        Spacing = 5,
                                                        Children = {
                                                            new Label {
                                                                VerticalOptions = LayoutOptions.CenterAndExpand,
                                                                HorizontalOptions = LayoutOptions.Start,
                                                                WidthRequest = LABEL_LARGURA,
                                                                HorizontalTextAlignment = TextAlignment.End,
                                                                LineBreakMode = LineBreakMode.TailTruncation,
                                                                FontSize = TAMANHO_FONTE,
                                                                Text = "Quantidade:"
                                                            },
                                                            _quantidadeLabel
                                                        }
                                                    },
                                                    */
                                                    new StackLayout {
                                                        Orientation = StackOrientation.Horizontal,
                                                        VerticalOptions = LayoutOptions.Start,
                                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                                        Spacing = 5,
                                                        Children = {
                                                            new Label {
                                                                VerticalOptions = LayoutOptions.CenterAndExpand,
                                                                HorizontalOptions = LayoutOptions.Start,
                                                                WidthRequest = LABEL_LARGURA,
                                                                HorizontalTextAlignment = TextAlignment.End,
                                                                LineBreakMode = LineBreakMode.TailTruncation,
                                                                FontSize = TAMANHO_FONTE,
                                                                Text = "Volume:"
                                                            },
                                                            _volumeLabel
                                                        }
                                                    },
                                                    new StackLayout {
                                                        Orientation = StackOrientation.Horizontal,
                                                        VerticalOptions = LayoutOptions.Start,
                                                        HorizontalOptions = LayoutOptions.FillAndExpand,
                                                        Spacing = 5,
                                                        Children = {
                                                            new Label {
                                                                VerticalOptions = LayoutOptions.CenterAndExpand,
                                                                HorizontalOptions = LayoutOptions.Start,
                                                                WidthRequest = LABEL_LARGURA,
                                                                HorizontalTextAlignment = TextAlignment.End,
                                                                LineBreakMode = LineBreakMode.TailTruncation,
                                                                FontSize = TAMANHO_FONTE,
                                                                Text = "Descrição:"
                                                            },
                                                            _descricaoLabel
                                                        }
                                                    }
                                                }
                                            },
                                            _quantidadeButton
                                        }
                                    }
                                }
                            }
                        }
                    },
                    _totalView
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            _totalView.vincularComCarrinho();
        }

        protected override void OnDisappearing()
        {
            _totalView.desvincularComCarrinho();
            base.OnDisappearing();
        }

        private void inicializarComponente()
        {

            _fotoImage = new Image
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.CenterAndExpand,
                WidthRequest = 400,
                HeightRequest = 300,
                Aspect = Aspect.AspectFit
            };
            _fotoImage.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
            _categoriaLabel = new Label
            {
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                TextColor = Color.FromHex("#777777")
            };
            _categoriaLabel.SetBinding(Label.TextProperty, new Binding("Categoria.Nome"));
            _codigoLabel = new Label
            {
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                TextColor = Color.FromHex("#777777")
            };
            _codigoLabel.SetBinding(Label.TextProperty, new Binding("Codigo"));
            _descricaoLabel = new Label
            {
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                TextColor = Color.FromHex("#777777")
            };
            _descricaoLabel.SetBinding(Label.TextProperty, new Binding("Descricao"));
            _moedaValorLabel = new Label
            {
                Text = "R$",
                FontSize = 11,
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Start
            };
            _moedaValorLabel.SetBinding(Label.TextColorProperty, new Binding("PromocaoCor"));
            _valorFinalLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.End,
                FontFamily = Estilo.Current.FontDefaultBold,
                FontAttributes = FontAttributes.Bold,
                FontSize = 24
            };
            _valorFinalLabel.SetBinding(Label.TextProperty, new Binding("ValorFinal", stringFormat: "{0:N2}"));
            _valorFinalLabel.SetBinding(Label.TextColorProperty, new Binding("PromocaoCor"));

            _volumeLabel = new Label
            {
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                TextColor = Color.FromHex("#777777")
            };
            _volumeLabel.SetBinding(Label.TextProperty, new Binding("VolumeStr"));

            _quantidadeLabel = new Label
            {
                FontAttributes = FontAttributes.Bold,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                TextColor = Color.FromHex("#777777")
            };
            _quantidadeLabel.SetBinding(Label.TextProperty, new Binding("Quantidade"));

            _moedaPromocaoLabel = new Label
            {
                Text = "R$",
                FontSize = 11,
                //TextColor = Estilo.Current.D,
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Start
            };
            _valorPromocaoLabel = new Label
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.End,
                FontFamily = Estilo.Current.FontDefaultBold,
                //TextColor = Estilo.Current.DangerColor,
                FontAttributes = FontAttributes.Bold,
                FontSize = 24
            };
            _valorPromocaoLabel.SetBinding(Label.TextProperty, new Binding("Valor", stringFormat: "{0:N2}"));

            var valorLayout = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 0, 0, 3),
                Children = {
                    _moedaPromocaoLabel,
                    _valorPromocaoLabel
                }
            };
            AbsoluteLayout.SetLayoutBounds(valorLayout, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(valorLayout, AbsoluteLayoutFlags.All);

            var linha = new BoxView
            {
                VerticalOptions = LayoutOptions.Center,
                HorizontalOptions = LayoutOptions.Fill,
                BackgroundColor = Estilo.Current.DangerColor,
                HeightRequest = 1,
            };
            AbsoluteLayout.SetLayoutBounds(linha, new Rectangle(0, 0, 1, 1));
            AbsoluteLayout.SetLayoutFlags(linha, AbsoluteLayoutFlags.All);

            _promocaoStack = new AbsoluteLayout
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Children = {
                    valorLayout,
                    linha
                }
            };
            _promocaoStack.SetBinding(AbsoluteLayout.IsVisibleProperty, new Binding("EmPromocao"));

            _quantidadeButton = new QuantidadeControl
            {
                Margin = new Thickness(0, 5, 5, 0),
                VerticalOptions = LayoutOptions.CenterAndExpand,
                HorizontalOptions = LayoutOptions.End,
                FontFamily = Estilo.Current.FontDefaultBold,
                HeightRequest = 120
            };
            _quantidadeButton.SetBinding(QuantidadeControl.QuantidadeProperty, new Binding("QuantidadeCarrinho"));
            _quantidadeButton.SetBinding(QuantidadeControl.ProdutoProperty, new Binding("."));

            _destaqueIcon = new IconImage
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Icon = "fa-star",
                IconColor = Color.FromHex("#ffc500"),
                IconSize = 24
            };
            _destaqueIcon.SetBinding(Label.IsVisibleProperty, new Binding("Destaque"));

            _totalView = new TotalCarrinhoView {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.End,
                ExibeQuantidade = true,
                ExibeTotal = true
            };
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();
            if (BindingContext != null) {
                var produto = (ProdutoInfo)BindingContext;
                if (produto.Destaque)
                {
                    ToolbarItems.Insert(0, new IconToolbarItem
                    {
                        Text = "Destaque",
                        Icon = "fa-star",
                        IconColor = Estilo.Current.BarTitleColor,
                        Order = ToolbarItemOrder.Primary
                    });
                }
            }
        }
    }

}