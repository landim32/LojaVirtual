using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Veiculo.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Veiculo.Pages
{
    public class VeiculoBuscaPage : ContentPage
    {
        private Entry _CidadeOrigem;
        private Entry _CidadeDestino;
        private DatePicker _DataInicio;
        private DatePicker _DataFim;
        private TimePicker _HoraInicio;
        private TimePicker _HoraFim;
        private Button _BuscarButton;

        public VeiculoBuscaPage()
        {
            Title = "Pesquisar";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(10, 10),
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Retirar o carro em:",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-map-marker",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _CidadeOrigem
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-calendar",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _DataInicio,
                            new IconImage {
                                Icon = "fa-clock-o",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _HoraInicio
                        }
                    },
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Devolução:",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-map-marker",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _CidadeDestino
                        }
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-calendar",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _DataFim,
                            new IconImage {
                                Icon = "fa-clock-o",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _HoraFim
                        }
                    },
                    _BuscarButton
                }
            };
        }

        private void inicializarComponente() {
            _CidadeOrigem = new Entry
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Minha posição atual",
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            _CidadeDestino = new Entry
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Text = "Minha posição atual",
                Style = Estilo.Current[Estilo.ENTRY_PADRAO]
            };
            _DataInicio = new DatePicker {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _DataFim = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraInicio = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };
            _HoraFim = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _BuscarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Text = "Buscar",
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL]
            };
            _BuscarButton.Clicked += (sender, e) =>
            {
                Navigation.PushAsync(new VeiculoListaPage());
            };
        }
    }
}