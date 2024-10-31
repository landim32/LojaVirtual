using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.BLL;
using Emagine.Login.Factory;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Model;
using FormsPlugin.Iconize;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Pages
{
    public class FreteAgendaPage : ContentPage
    {
        private Switch _UsaDataEntrega;

        private DatePicker _DataRetirada;
        private DatePicker _DataEntrega;
        private TimePicker _HoraRetirada;
        private TimePicker _HoraEntrega;

        private Editor _ObservacaoEntry;
        private StackLayout _conteudoLayout;
        private StackLayout _entregaLayout;

        private Button _EnviarButton;

        public event EventHandler<FreteInfo> AoAgendar;

        private FreteInfo _frete;

        public FreteInfo Frete {
            get {
                return _frete;
            }
            set {
                _frete = value;
                if (_frete != null) {
                    if (value.DataRetirada != null) {
                        _DataRetirada.Date = (DateTime)value.DataRetirada;
                        _DataRetirada.Date.AddHours(((DateTime)value.DataRetirada).Hour * -1);
                        _DataRetirada.Date.AddMinutes(((DateTime)value.DataRetirada).Minute * -1);
                        _HoraRetirada.Time = TimeSpan.Parse(((DateTime)value.DataRetirada).Hour + ":" + ((DateTime)value.DataRetirada).Minute);
                    }

                    if (value.DataEntrega != null) {
                        _UsaDataEntrega.IsToggled = true;
                        _DataEntrega.Date = (DateTime)value.DataEntrega;
                        _DataEntrega.Date.AddHours(((DateTime)value.DataEntrega).Hour * -1);
                        _DataEntrega.Date.AddMinutes(((DateTime)value.DataEntrega).Minute * -1);
                        _HoraEntrega.Time = TimeSpan.Parse(((DateTime)value.DataEntrega).Hour + ":" + ((DateTime)value.DataEntrega).Minute);
                    }
                    else {
                        _UsaDataEntrega.IsToggled = false;
                    }
                }
            }
        }

        public FreteAgendaPage()
        {
            inicializarComponente();

            _entregaLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    new Label {
                        HorizontalOptions = LayoutOptions.Fill,
                        VerticalOptions = LayoutOptions.Start,
                        Text = "Data hora máxima para entrega:",
                        Style = Estilo.Current[Estilo.LABEL_CONTROL]
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
                            _DataEntrega,
                            new IconImage {
                                Icon = "fa-clock-o",
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO]
                            },
                            _HoraEntrega
                        }
                    }
                }
            };

            _conteudoLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Children = {
                    _entregaLayout
                }
            };

            Content = new ScrollView {
                Orientation = ScrollOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    HorizontalOptions = LayoutOptions.Fill,
                    VerticalOptions = LayoutOptions.Start,
                    Padding = new Thickness(3, 3),
                    Children = {
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Text = "Data hora da Retirada:",
                            Style = Estilo.Current[Estilo.LABEL_CONTROL]
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
                                _DataRetirada,
                                new IconImage {
                                    Icon = "fa-clock-o",
                                    HorizontalOptions = LayoutOptions.Start,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.ICONE_PADRAO]
                                },
                                _HoraRetirada
                            }
                        },
                        _conteudoLayout,
                        new StackLayout{
                            Orientation = StackOrientation.Horizontal,
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Spacing = 5,
                            Children = {
                                _UsaDataEntrega,
                                new Label {
                                    HorizontalOptions = LayoutOptions.Fill,
                                    VerticalOptions = LayoutOptions.Center,
                                    Style = Estilo.Current[Estilo.LABEL_SWITCH],
                                    Text = "Não se aplica"
                                }
                            }
                        },
                        new Label {
                            HorizontalOptions = LayoutOptions.Fill,
                            VerticalOptions = LayoutOptions.Start,
                            Style = Estilo.Current[Estilo.LABEL_CONTROL],
                            Text = "Observação:"
                        },
                        _ObservacaoEntry,
                        _EnviarButton
                    }
                }
            };
        }
        
        private void inicializarComponente() {
            _ObservacaoEntry = new Editor {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                HeightRequest = 80,
                FontSize = 14
            };
           
            _DataRetirada = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraRetirada = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _DataEntrega = new DatePicker
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_DATA]
            };
            _HoraEntrega = new TimePicker
            {
                WidthRequest = 100,
                HorizontalOptions = LayoutOptions.Start,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.ENTRY_TEMPO]
            };

            _UsaDataEntrega = new Switch
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                IsToggled = false
            };
            _UsaDataEntrega.Toggled += (sender, e) => {
                if (e.Value)
                {
                    if (_conteudoLayout.Children.Contains(_entregaLayout))
                    {
                        _conteudoLayout.Children.Remove(_entregaLayout);
                    }
                }
                else
                {
                    if (!_conteudoLayout.Children.Contains(_entregaLayout))
                    {
                        _conteudoLayout.Children.Add(_entregaLayout);
                    }
                }
            };

            _EnviarButton = new Button
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "AGENDAR"
            };
            _EnviarButton.Clicked += (sender, e) => {
                var frete = this.Frete;
                frete.DataRetirada = new DateTime(_DataRetirada.Date.Year, _DataRetirada.Date.Month, _DataRetirada.Date.Day, 0, 0, 0, DateTimeKind.Unspecified);
                frete.DataRetirada = ((DateTime)frete.DataRetirada).AddHours(_HoraRetirada.Time.Hours);
                frete.DataRetirada = ((DateTime)frete.DataRetirada).AddMinutes(_HoraRetirada.Time.Minutes);

                if (!_UsaDataEntrega.IsToggled)
                {
                    frete.DataEntrega = new DateTime(_DataEntrega.Date.Year, _DataEntrega.Date.Month, _DataEntrega.Date.Day, 0, 0, 0, DateTimeKind.Unspecified);
                    frete.DataEntrega = ((DateTime)frete.DataEntrega).AddHours(_HoraEntrega.Time.Hours);
                    frete.DataEntrega = ((DateTime)frete.DataEntrega).AddMinutes(_HoraEntrega.Time.Minutes);
                }
                else {
                    frete.DataEntrega = frete.DataRetirada;
                }
                frete.Observacao = _ObservacaoEntry.Text;
                AoAgendar?.Invoke(this, frete);
            };
        }
        
    }
     
}

