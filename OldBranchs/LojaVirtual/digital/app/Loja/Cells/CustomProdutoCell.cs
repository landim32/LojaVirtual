using Emagine.Produto.Cells;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Loja.Cells
{
    public class CustomProdutoCell : ProdutoCell
    {
        public CustomProdutoCell()
        {
            View = new Frame
            {
                CornerRadius = 5,
                Padding = 2,
                Margin = new Thickness(2, 2),
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
                                _volumeLabel,
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

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            _nomeLabel.FontSize = 17;
            _nomeLabel.LineBreakMode = LineBreakMode.WordWrap;
        }
    }
}