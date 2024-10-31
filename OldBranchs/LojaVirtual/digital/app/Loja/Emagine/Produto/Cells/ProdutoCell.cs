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
    public class ProdutoCell: ProdutoBaseCell
    {
        protected QuantidadeControl _quantidadeButton;
        protected IconImage _compartilharButton;

        public ProdutoCell() {
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
                    HorizontalOptions = LayoutOptions.FillAndExpand,
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
                                        _destaqueIcon,
                                        _compartilharButton
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
                        },
                        _quantidadeButton
                    }
                }
            };
        }

        protected override void inicializarComponente() {
            base.inicializarComponente();

            _quantidadeButton = new QuantidadeControl {
                Margin = new Thickness(0, 5, 5, 0),
                VerticalOptions = LayoutOptions.CenterAndExpand,
                HorizontalOptions = LayoutOptions.End,
                FontFamily = Estilo.Current.FontDefaultBold,
                WidthRequest = 40,
                HeightRequest = 120
            };
            _quantidadeButton.SetBinding(QuantidadeControl.QuantidadeProperty, new Binding("QuantidadeCarrinho"));
            _quantidadeButton.SetBinding(QuantidadeControl.ProdutoProperty, new Binding("."));

            _compartilharButton = new IconImage
            {
                VerticalOptions = LayoutOptions.CenterAndExpand,
                HorizontalOptions = LayoutOptions.End,
                Icon = "fa-share-alt",
                IconColor = Estilo.Current.PrimaryColor,
                IconSize = 24,
                Margin = new Thickness(0, 2)
            };
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += (sender, e) =>
            {
                if (!CrossShare.IsSupported)
                    return;

                var produto = (ProdutoInfo)BindingContext;
                if (produto == null) {
                    return;
                }

                CrossShare.Current.Share(new ShareMessage
                {
                    //Title = produto.Nome,
                    //Text = "R$ " + produto.ValorFinal.ToString("N2"),
                    //Url = "http://smartappcompras.com.br/site/" + produto.Slug
                    Url = produto.Url
                });
            };
            _compartilharButton.GestureRecognizers.Add(tapGestureRecognizer);

        }
    }
}
