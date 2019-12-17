using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Cells;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using FormsPlugin.Iconize;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class FreteListaPage : ContentPage
    {
        private StackLayout _mainLayout;
        private ListView _freteListView;
        private IconToolbarItem _filtroToolbar;
        private Button _NovoButton;

        private IList<FreteInfo> _fretes;

        public IList<FreteInfo> Fretes {
            get {
                return _fretes;
            }
            set {
                _fretes = value;
                _freteListView.ItemsSource = _fretes;
            }
        }

        public bool FiltroBotao {
            get {
                return ToolbarItems.Contains(_filtroToolbar);
            }
            set {
                if (value) {
                    if (!ToolbarItems.Contains(_filtroToolbar)) {
                        ToolbarItems.Add(_filtroToolbar);
                    }
                }
                else {
                    if (ToolbarItems.Contains(_filtroToolbar)) {
                        ToolbarItems.Remove(_filtroToolbar);
                    }
                }
            }
        }

        public bool NovoBotao {
            get {
                return _mainLayout.Children.Contains(_NovoButton);
            }
            set {
                if (value) {
                    if (!_mainLayout.Children.Contains(_NovoButton)) {
                        _mainLayout.Children.Add(_NovoButton);
                    }
                }
                else {
                    if (_mainLayout.Children.Contains(_NovoButton)) {
                        _mainLayout.Children.Remove(_NovoButton);
                    }
                }
            }
        }

        public FreteListaPage()
        {
            Title = "Meus Fretes";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            Padding = new Thickness(3, 5);
            inicializarComponente();

            this.ToolbarItems.Add(_filtroToolbar);

            _mainLayout = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _freteListView,
                    _NovoButton
                }
            };
            Content = _mainLayout;
        }
        
        private void inicializarComponente() {

            _filtroToolbar = new IconToolbarItem()
            {
                Icon = "fa-filter",
                IconColor = Estilo.Current.BarTitleColor,
                Command = new Command((object obj) =>
                {
                    var filtroPage = new FreteFiltroPage();
                    filtroPage.Filtered += (sender, e) =>
                    {
                        var aux = _fretes;
                        if (e.Tipo != null)
                        {
                            aux = aux.Where(x => x.Veiculos.Contains(e.Tipo)).ToList();
                        }
                        if (e.Destino != null)
                        {
                            aux = aux.Where(x => x.Locais.Where(y => y.Tipo == FreteLocalTipoEnum.Destino && y.Uf == e.Destino).Count() > 0).ToList();
                        }
                        if (e.Origem != null)
                        {
                            aux = aux.Where(x => x.Locais.Where(y => y.Tipo == FreteLocalTipoEnum.Saida && y.Uf == e.Origem).Count() > 0).ToList();
                        }
                        _freteListView.ItemsSource = aux;
                    };
                    Navigation.PushAsync(filtroPage);
                })
            };

            _freteListView = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO],
                HasUnevenRows = true,
                RowHeight = -1,
                //ItemTemplate = new DataTemplate(typeof(FreteEmpresaCell))
                ItemTemplate = new DataTemplate(typeof(FreteCell))
            };
            _freteListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _freteListView.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;

                FreteInfo frete = (FreteInfo)((ListView)sender).SelectedItem;
                _freteListView.SelectedItem = null;
                await Navigation.PushAsync(new FretePage {
                    Title = frete.SituacaoStr,
                    Frete = frete
                });
            };

            _NovoButton = new Button()
            {
                Text = "Novo frete",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _NovoButton.Clicked += (sender, e) =>
            {
                Navigation.PushAsync(new FreteFormPage());
            };
        }
    }
}

