using Acr.UserDialogs;
using Emagine.Banner.Controls;
using Emagine.Banner.Model;
using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Controls;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class CategoriaGridPage : CategoriaBasePage
    {
        private Grid _categoriaGrid;
        private IList<CategoriaInfo> _categorias;

        public CategoriaGridPage()
        {
            _mainLayout = new StackLayout
            {
                //Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _bannerView,
                    _buscaBar,
                    new ScrollView {
                        Orientation = ScrollOrientation.Vertical,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.FillAndExpand,
                        Content = _categoriaGrid
                    },
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

        public IList<CategoriaInfo> Categorias
        {
            get
            {
                return _categorias;
            }
            set
            {
                _categorias = value;
                if (_categorias != null)
                {
                    atualizarCategoria(_categorias);
                }
                else
                {
                    _categoriaGrid.Children.Clear();
                }
            }
        }

        protected override void executarAtualizarCategoria(IList<CategoriaInfo> itens)
        {
            this.Categorias = itens;
        }

        protected override void inicializarComponente() {

            base.inicializarComponente();
            _categoriaGrid = new Grid
            {
                Margin = 5,
                RowSpacing = 10,
                ColumnSpacing = 10
            };
            _categoriaGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _categoriaGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _categoriaGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
        }

        private void atualizarCategoria(IList<CategoriaInfo> categorias)
        {
            _categoriaGrid.Children.Clear();
            int left = 0, top = 0;
            foreach (var categoria in categorias)
            {
                atualizarCategoria(categoria, left, top);
                left++;
                if (left > 2)
                {
                    left = 0;
                    top++;
                }
            }
        }

        private void atualizarCategoria(CategoriaInfo categoria, int left, int top)
        {
            var seguimentoView = new CategoriaView
            {
                Categoria = categoria
            };
            seguimentoView.AoClicar += async (sender, e) =>
            {
                //AoClicar?.Invoke(sender, e);
                await abrirCategoria(e);
            };
            _categoriaGrid.Children.Add(seguimentoView, left, top);
        }
    }
}