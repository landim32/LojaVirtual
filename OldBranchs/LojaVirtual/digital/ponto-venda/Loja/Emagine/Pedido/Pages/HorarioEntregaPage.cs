using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Pedido.Cells;
using Emagine.Pedido.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pedido.Pages
{
    public class HorarioEntregaPage : ContentPage
    {
        private DatePicker _diaEntregaPicker;
        private Label _horarioLabel;
        private ListView _horarioListView;

        private IList<PedidoHorarioInfo> _horarios;
        public event EventHandler<string> AoSelecionar;

        //public PedidoInfo Pedido { get; set; }

        public DateTime DiaEntrega {
            get {
                return _diaEntregaPicker.Date;
            }
            set {
                _diaEntregaPicker.Date = value;
            }
        }

        public IList<PedidoHorarioInfo> Horarios {
            get {
                return _horarios;
            }
            set {
                _horarioListView.ItemsSource = null;
                _horarios = value;
                _horarioListView.ItemsSource = _horarios;
                _horarioLabel.IsVisible = (_horarios != null && _horarios.Count() > 0);
            }
        }

        public HorarioEntregaPage()
        {
            Title = "Meus Pedidos";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            _horarios = new List<PedidoHorarioInfo>();

            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    new Label {
                        Text = "Dia da Entrega:",
                        VerticalOptions = LayoutOptions.Start,
                        HorizontalOptions = LayoutOptions.Start,
                        Style = Estilo.Current[Estilo.LABEL_CONTROL],
                    },
                    new StackLayout {
                        Orientation = StackOrientation.Horizontal,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.Start,
                        Children = {
                            new IconImage {
                                Icon = "fa-calendar",
                                //IconSize = 20,
                                HorizontalOptions = LayoutOptions.Start,
                                VerticalOptions = LayoutOptions.Center,
                                Style = Estilo.Current[Estilo.ICONE_PADRAO],
                            },
                            _diaEntregaPicker
                        }
                    },
                    _horarioLabel,
                    _horarioListView
                }
            };
        }

        private void inicializarComponente()
        {
            _diaEntregaPicker = new DatePicker {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                MinimumDate = DateTime.Today
            };
            _diaEntregaPicker.DateSelected += (sender, e) => {
                if (_horarios != null && _horarios.Count() == 0) {
                    AoSelecionar?.Invoke(this, string.Empty);
                }
            };

            _horarioLabel = new Label
            {
                Text = "Selecione o horário de entrega:",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.LABEL_CONTROL],
            };

            _horarioListView = new ListView
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                ItemTemplate = new DataTemplate(typeof(PedidoHorarioCell))
            };
            _horarioListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _horarioListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                if (!(_diaEntregaPicker.Date >= DateTime.Today)) {
                    _horarioListView.SelectedItem = null;
                    UserDialogs.Instance.AlertAsync("Aviso", "Selecione uma data maior que hoje.", "Entendi");
                    return;
                }
                var horario = (PedidoHorarioInfo)((ListView)sender).SelectedItem;
                //_horarioListView.SelectedItem = null;

                AoSelecionar?.Invoke(this, horario.Horario);
            };
        }
    }
}