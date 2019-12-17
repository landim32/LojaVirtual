using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Produto.Controls;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using Plugin.Share;
using Plugin.Share.Abstractions;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class ProdutoBaseCell: ViewCell
    {
        protected Image _fotoImage;
        protected Label _nomeLabel;
        protected Label _descricaoLabel;
        protected Label _moedaValorLabel;
        protected Label _valorFinalLabel;
        protected Label _volumeLabel;
        protected Label _quantidadeLabel;
        protected AbsoluteLayout _promocaoStack;
        protected Label _moedaPromocaoLabel;
        protected Label _valorPromocaoLabel;
        protected IconImage _destaqueIcon;

        public ProdutoBaseCell() {
            inicializarComponente();
            View = new Frame
            {
                CornerRadius = 5,
                Padding = 2,
                Margin = new Thickness(2,2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Horizontal,
                    VerticalOptions = LayoutOptions.Start,
                    HorizontalOptions = LayoutOptions.Fill,
                    Children = {
                        _fotoImage,
                        new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Spacing = 0,
                            Children = {
                                new StackLayout {
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Children = {
                                        _nomeLabel,
                                        _destaqueIcon
                                    }
                                },
                                new StackLayout {
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Spacing = 5,
                                    Children = {
                                        new Label {
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start,
                                            Text = "Quantidade:"
                                        },
                                        _quantidadeLabel,
                                        new Label {
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start,
                                            Text = ", "
                                        },
                                        _volumeLabel
                                    }
                                },
                                _descricaoLabel,
                                new StackLayout {
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Margin = new Thickness(0, 0, 0, 3),
                                    Children = {
                                        _moedaValorLabel,
                                        _valorFinalLabel,
                                        _promocaoStack
                                    }
                                },
                            }
                        }
                    }
                }
            };
        }

        protected virtual void inicializarComponente() {

            _fotoImage = new Image
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                WidthRequest = 80,
                HeightRequest = 110,
                Aspect = Aspect.AspectFit
            };
            _fotoImage.SetBinding(Image.SourceProperty, new Binding("FotoUrl"));
            _nomeLabel = new Label {
                FontFamily = Estilo.Current.FontDefaultBold,
                FontSize = 20,
                FontAttributes = FontAttributes.Bold,
                LineBreakMode = LineBreakMode.TailTruncation,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                TextColor = Estilo.Current.PrimaryColor
            };
            _nomeLabel.SetBinding(Label.TextProperty, new Binding("Nome"));
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
            //_volumeLabel.SetBinding(Label.TextColorProperty, new Binding("PromocaoCor"));

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

            _destaqueIcon = new IconImage {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Icon = "fa-star",
                IconColor = Color.FromHex("#ffc500"),
                IconSize = 24
            };
            _destaqueIcon.SetBinding(Label.IsVisibleProperty, new Binding("Destaque"));
        }
    }
}
