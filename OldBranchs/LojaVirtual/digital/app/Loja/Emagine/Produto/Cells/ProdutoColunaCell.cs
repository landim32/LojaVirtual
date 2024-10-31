using Emagine.Base.Pages;
using Emagine.Produto.Controls;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class ProdutoColunaCell: ViewCell
    {
        private Grid _produtoGrid;
        private ProdutoView _coluna1;
        private ProdutoView _coluna2;
        private ProdutoView _coluna3;

        public ProdutoColunaCell() {
            inicializarComponente();
            View = _produtoGrid;
        }

        private void inicializarComponente() {

            _coluna1 = new ProdutoView {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
            };
            _coluna1.AoClicar += produtoClicar;
            _coluna2 = new ProdutoView
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
            };
            _coluna2.AoClicar += produtoClicar;
            _coluna3 = new ProdutoView
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
            };
            _coluna3.AoClicar += produtoClicar;

            _produtoGrid = new Grid
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                //Margin = new Thickness(2, 2),
                Margin = 1,
                RowSpacing = 1,
                ColumnSpacing = 3
            };
            _produtoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _produtoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _produtoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            _produtoGrid.Children.Add(_coluna1, 0, 0);
            _produtoGrid.Children.Add(_coluna2, 1, 0);
            _produtoGrid.Children.Add(_coluna3, 2, 0);
        }

        private void produtoClicar(object sender, ProdutoInfo produto)
        {
            var produtoPage = new ProdutoPage {
                Title = produto.Nome,
                Produto = produto
            };
            if (App.Current.MainPage is RootPage) {
                ((RootPage)App.Current.MainPage).PushAsync(produtoPage);
            }
            else {
                App.Current.MainPage = App.gerarRootPage(produtoPage);
            }
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();
            var produtoColuna = (ProdutoColunaInfo)BindingContext;
            if (produtoColuna != null)
            {
                _coluna1.BindingContext = produtoColuna.Coluna1;
                _coluna2.BindingContext = produtoColuna.Coluna2;
                _coluna3.BindingContext = produtoColuna.Coluna3;
                _coluna1.IsVisible = produtoColuna.Coluna1Visivel;
                _coluna2.IsVisible = produtoColuna.Coluna2Visivel;
                _coluna3.IsVisible = produtoColuna.Coluna3Visivel;
            }
        }
    }
}
