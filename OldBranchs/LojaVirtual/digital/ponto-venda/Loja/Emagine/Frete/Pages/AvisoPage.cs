using Emagine.Base.Estilo;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class AvisoPage : ContentPage
    {
        private ListView _lojaListView;

        public AvisoPage()
        {
            Title = "Veja suas opções";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    new Frame {
                        Margin = new Thickness(10, 10),
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Fill,
                        Content = new StackLayout {
                            Orientation = StackOrientation.Horizontal,
                            Spacing = 5,
                            VerticalOptions = LayoutOptions.Start,
                            HorizontalOptions = LayoutOptions.Fill,
                            Children = {
                                new IconImage {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Start,
                                    Icon = "fa-remove",
                                    IconSize = 18,
                                    IconColor = Color.Black
                                },
                                new Label {
                                    VerticalOptions = LayoutOptions.Start,
                                    HorizontalOptions = LayoutOptions.Fill,
                                    Text = "Você não tem aviso.",
                                    TextColor = Color.Black
                                }
                            }
                        }
                    },
                    _lojaListView,
                }
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            //var regraLoja = LojaFactory.create();
            //_lojaListView.ItemsSource = regraLoja.listar();
        }

        private void inicializarComponente()
        {
            _lojaListView = new ListView
            {
                HasUnevenRows = true,
                RowHeight = -1,
                ItemTemplate = new DataTemplate(typeof(TextCell)),
                Style = Estilo.Current[Estilo.LISTA_PADRAO]
            };
            _lojaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _lojaListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                /*
                var loja = (LojaInfo)((ListView)sender).SelectedItem;
                _lojaListView.SelectedItem = null;

                Navigation.PushAsync(new ProdutoListaPage
                {
                    Title = "Em Destaque"
                });
                */
            };
        }
    }
}