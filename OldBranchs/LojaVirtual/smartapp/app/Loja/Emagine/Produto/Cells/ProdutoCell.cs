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
        public ProdutoCell() {
            //inicializarComponente();
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
                                            Text = "Quantidade:"
                                        },
                                        _quantidadeLabel,
                                        new Label {
                                            VerticalOptions = LayoutOptions.Start,
                                            HorizontalOptions = LayoutOptions.Start,
                                            Style = Estilo.Current[EstiloProduto.PRODUTO_LABEL],
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
                                        _valorLabel,
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
    }
}
