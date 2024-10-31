using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class ProdutoCarrinhoCell: ProdutoCell
    {
        public ProdutoCarrinhoCell() {
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
                    HorizontalOptions = LayoutOptions.FillAndExpand,
                    Children = {
                        _fotoImage,
                        new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.FillAndExpand,
                            Spacing = 0,
                            Children = {
                                _nomeLabel,
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
                                            Text = "Estoque:"
                                        },
                                        _quantidadeLabel
                                    }
                                },
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Vertical,
                            VerticalOptions = LayoutOptions.FillAndExpand,
                            HorizontalOptions = LayoutOptions.Start,
                            Padding = new Thickness(0, 0, 0, 15),
                            Children = {
                                _compartilharButton,
                                _removerButton
                            }
                        },
                        _quantidadeButton
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

        private CarrinhoPage buscarPagina(Element elemento) {
            if (elemento == null) {
                return null;
            }
            if (elemento is CarrinhoPage) {
                return (CarrinhoPage)elemento;
            }
            else {
                return buscarPagina(elemento.Parent);
            }
        }

        protected async override void removerProduto(ProdutoInfo produto)
        {
            //if (await UserDialogs.Instance.ConfirmAsync("Tem certeza?", "Aviso", "Sim", "Não")) {
            if (await UserDialogs.Instance.ConfirmAsync("Quer mesmo excluir esse produto?", "Aviso", "Sim", "Não")) {
                var listaPage = buscarPagina(this.Parent);
                if (listaPage != null) {
                    listaPage.excluir(produto);
                }
            }
        }
    }
}
