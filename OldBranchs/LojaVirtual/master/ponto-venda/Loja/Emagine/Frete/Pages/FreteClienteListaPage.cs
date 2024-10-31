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
    [Obsolete("Use FreteListaPage")]
    public class FreteClienteListaPage : ContentPage
    {
        private StackLayout _mainLayout;
        private ListView _freteListView;
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

        public FreteClienteListaPage()
        {
            Title = "Meus Fretes";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();

            this.ToolbarItems.Add(new IconToolbarItem()
            {
                Icon = "fa-filter",
                IconColor = Estilo.Current.BarTitleColor,
                Command = new Command((object obj) => {
                    var filtroPage = new FreteFiltroPage();
                    filtroPage.Filtered += (sender, e) => {
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
            });

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

        /*
        public async Task<List<FreteInfo>> listarFreteAsync()
        {
            var ret = await FreteFactory.create().listar();
            var auxEstados = new List<FreteLocalInfo>();
            foreach (var entrega in ret)
            {
                foreach (var local in entrega.Locais)
                {
                    auxEstados.Add(local);
                }
            }
            _filtro.Estados = auxEstados.GroupBy(x => x.Uf).Select(y => y.First()).Where(a => a.Uf != null).Select(z => z.Uf).ToList();
            return ret;
        }
        */

        private void inicializarComponente()
        {

            _freteListView = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO],
                HasUnevenRows = true,
                RowHeight = -1,
                ItemTemplate = new DataTemplate(typeof(FreteEmpresaCell))
            };

            _freteListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _freteListView.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;
                
                FreteInfo entrInfo = (FreteInfo)((ListView)sender).SelectedItem;
                if(entrInfo.Situacao == FreteSituacaoEnum.ProcurandoMotorista){
                    await Navigation.PushAsync(new FreteFormPage(entrInfo));
                }
                _freteListView.SelectedItem = null;

            };

            _NovoButton = new Button()
            {
                Text = "Novo frete",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Margin = 10,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _NovoButton.Clicked += (sender, e) =>
            {
                Navigation.PushAsync(new FreteFormPage());
            };
        }

        /*
        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_Inicio)
            {
                _Inicio = false;
                UserDialogs.Instance.ShowLoading("carregando...");
                _listaFrete = await listarFreteAsync();
                _freteListView.ItemsSource = _listaFrete;
                UserDialogs.Instance.HideLoading();
            }
        }
        */
    }
}

