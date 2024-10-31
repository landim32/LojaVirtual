using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Loja.Cells
{
    public class CustomProdutoCell: ProdutoCell
    {
        public CustomProdutoCell() {
            View = new Frame
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[EstiloProduto.PRODUTO_FRAME],
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
                                /*
                                new StackLayout {
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Spacing = 5,
                                    Children = {
                                        new Label {
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start,
                                            Style = Estilo.Current[EstiloProduto.PRODUTO_LABEL],
                                            Text = "Volume:"
                                        },
                                        _volumeLabel
                                    }
                                },
                                */
                                _descricaoLabel,
                                new StackLayout {
                                    Orientation = StackOrientation.Horizontal,
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.FillAndExpand,
                                    Margin = new Thickness(0, 0, 0, 3),
                                    Children = {
                                        _moedaValorLabel,
                                        _valorLabel,
                                        //_promocaoStack
                                    }
                                },
                            }
                        },
                        _quantidadeButton
                    }
                }
            };
        }
    }
}
