using Emagine.Base.Estilo;
using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Cells
{
    public class ListaCompraCell: ViewCell
    {
        private Label _NomeLabel;

        public ListaCompraCell() {
            inicilizarComponente();
            inicializarMenu();
            View = new StackLayout
            {
                Orientation = StackOrientation.Horizontal,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(0, 7),
                Children = {
                    _NomeLabel
                }
            };
        }

        private ListaCompraPage buscarPagina(Element elemento) {
            if (elemento == null) {
                return null;
            }
            if (elemento is ListaCompraPage) {
                return (ListaCompraPage)elemento;
            }
            else {
                return buscarPagina(elemento.Parent);
            }
        }

        private void inicializarMenu()
        {
            var removerButton = new MenuItem
            {
                Text = "Remover",
                Icon = "fa-remove",
                IsDestructive = true,
                //IconColor = Estilo.Current.BarTitleColor,
            };
            removerButton.SetBinding(MenuItem.CommandParameterProperty, new Binding("."));
            removerButton.Clicked += (sender, e) =>
            {
                var menu = (MenuItem)sender;
                var listaCompraPage = buscarPagina(this.Parent);
                if (listaCompraPage != null)
                {
                    var palavraChave = (string)menu.CommandParameter;
                    listaCompraPage.removerPalavraChave(palavraChave);
                }
            };
            ContextActions.Add(removerButton);
        }

        private void inicilizarComponente() {
            _NomeLabel = new Label {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Center,
                Style = Estilo.Current[Estilo.LISTA_ITEM]
            };
            _NomeLabel.SetBinding(Label.TextProperty, new Binding("."));
        }
    }
}
