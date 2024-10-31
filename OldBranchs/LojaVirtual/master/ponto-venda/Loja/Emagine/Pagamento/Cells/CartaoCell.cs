using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Cells
{
    public class CartaoCell : ViewCell
    {
        private IconImage _IconeImage;
        private Label _BandeiraLabel;
        private Label _NumeroLabel;

        public CartaoCell()
        {
            inicializarMenu();
            inicializarComponente();

            View = new Frame
            {
                CornerRadius = 8,
                Padding = new Thickness(8, 12),
                Margin = new Thickness(2, 2),
                BackgroundColor = Color.White,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Spacing = 3,
                    Children = {
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _IconeImage,
                                _BandeiraLabel,
                            }
                        },
                        new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                new IconImage{
                                    HorizontalOptions = LayoutOptions.Start,
                                    VerticalOptions = LayoutOptions.Center,
                                    Icon = "fa-credit-card",
                                    IconSize = 20,
                                    WidthRequest = 26,
                                    IconColor = Estilo.Current.PrimaryColor
                                },
                                _NumeroLabel
                            }
                        }
                    }
                },
            };
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
                var listaPage = buscarPagina(this.Parent);
                if (listaPage != null)
                {
                    var cartao = (CartaoInfo)menu.CommandParameter;
                    listaPage.excluir(cartao);
                }
            };
            ContextActions.Add(removerButton);
        }

        private CartaoListaPage buscarPagina(Element elemento)
        {
            if (elemento == null)
            {
                return null;
            }
            if (elemento is CartaoListaPage)
            {
                return (CartaoListaPage)elemento;
            }
            else
            {
                return buscarPagina(elemento.Parent);
            }
        }

        private void inicializarComponente()
        {
            _IconeImage = new IconImage
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Center,
                IconSize = 20,
                WidthRequest = 26,
                IconColor = Estilo.Current.PrimaryColor
            };
            _IconeImage.SetBinding(IconImage.IconProperty, new Binding("BandeiraIcone"));
            _BandeiraLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 16
            };
            _BandeiraLabel.SetBinding(Label.TextProperty, new Binding("BandeiraStr"));
            _NumeroLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                FontSize = 16
            };
            _NumeroLabel.SetBinding(Label.TextProperty, new Binding("Nome", stringFormat: "Terminado em {0}"));

        }
    }
}