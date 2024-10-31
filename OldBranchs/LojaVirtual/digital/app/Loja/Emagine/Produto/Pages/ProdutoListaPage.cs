using Acr.UserDialogs;
using Emagine.Base.Estilo;
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
    public class ProdutoListaPage : ProdutoBasePage
    {
        public ProdutoListaPage()
        {
            Content = new StackLayout
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _ProdutoListView,
                    _totalView
                }
            };
        }

        protected override void inicializarMenu()
        {
            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Buscar",
                Icon = "fa-search",
                IconColor = Estilo.Current.BarTitleColor,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() =>
                {
                    var buscaPage = ProdutoUtils.gerarProdutoBusca();
                    Navigation.PushAsync(buscaPage);
                })
            });
            base.inicializarMenu();
        }
    }
}