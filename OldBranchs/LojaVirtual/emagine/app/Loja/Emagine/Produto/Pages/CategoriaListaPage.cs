using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class CategoriaListaPage : CategoriaBasePage
    {
        /*
        private Button _destaqueButton;
        private Button _promocaoButton;
        private Grid _menuGrid;
        */
        private ListView _categoriaListView;

        public CategoriaListaPage()
        {
            _mainLayout = new StackLayout
            {
                //Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    //_menuGrid,
                    _buscaBar,
                    _categoriaListView,
                    new StackLayout {
                        Orientation = StackOrientation.Vertical,
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Margin = new Thickness(5, 0),
                        Spacing = 0,
                        Children = {
                            new Label {
                                VerticalOptions = LayoutOptions.Start,
                                HorizontalOptions = LayoutOptions.Fill,
                                HorizontalTextAlignment = TextAlignment.Center,
                                Text = "Você está comprando em:",
                                FontSize = 10
                            },
                            _empresaLabel
                        }
                    }
                }
            };
            Content = _mainLayout;
        }

        protected override void executarAtualizarCategoria(IList<CategoriaInfo> itens) {
            _categoriaListView.ItemsSource = itens;
        }
        
        protected override void inicializarComponente() {

            base.inicializarComponente();
            /*
            _destaqueButton = new Button
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Destaques",
                FontSize = 14,
                HeightRequest = 40,
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO]
            };

            _promocaoButton = new Button
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Promoções",
                FontSize = 14,
                HeightRequest = 40,
                Style = Estilo.Current[EstiloProduto.PRODUTO_CARRINHO_BOTAO]
            };

            _menuGrid = new Grid
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = 1,
                RowSpacing = 1,
                ColumnSpacing = 3
            };
            _menuGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _menuGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            _menuGrid.Children.Add(_destaqueButton, 0, 0);
            _menuGrid.Children.Add(_promocaoButton, 1, 0);
            */

            _categoriaListView = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(HorarioEntregaCell))
            };
            _categoriaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _categoriaListView.ItemTapped += async (sender, e) => {
                if (e == null)
                    return;
                var categoria = (CategoriaInfo)((ListView)sender).SelectedItem;
                _categoriaListView.SelectedItem = null;

                await abrirCategoria(categoria);
            };
        }
    }
}