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
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    [Obsolete("Use FreteListaPage")]
    public class FreteMotoristaListaPage : ContentPage
    {
        private ListView _freteListView;
        private List<FreteInfo> _listaFrete;
        private bool _Inicio = true;
        private FreteFiltroPage _filtro;

        public FreteMotoristaListaPage()
        {
            Title = "Meus Fretes";
            inicializarComponente();

            this.ToolbarItems.Add(new ToolbarItem()
            {
                Icon = "filtro.png",
                Command = new Command((object obj) => {
                    Navigation.PushAsync(_filtro); 
                })
            }); 

            Content = _freteListView;
        }

        private void filtrar(object sender, FreteFiltroInfo e)
        {
            var aux = _listaFrete;
            if(e.Tipo != null){
                aux = aux.Where(x => x.Veiculos.Contains(e.Tipo)).ToList(); 
            }
            if(e.Destino != null)
            {
                aux = aux.Where(x => x.Locais.Where(y => y.Tipo == FreteLocalTipoEnum.Destino && y.Uf == e.Destino).Count() > 0).ToList(); 
            }
            if (e.Origem != null)
            {
                aux = aux.Where(x => x.Locais.Where(y => y.Tipo == FreteLocalTipoEnum.Origem && y.Uf == e.Origem).Count() > 0).ToList();
            }
            _freteListView.ItemsSource = aux;
        }

        public async Task<List<FreteInfo>> listarFreteAsync()
        {
            var ret = await FreteFactory.create().listar();
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();
            var aux = ret.Where(x => x.IdMotorista == motorista.Id);
            if(aux != null){
                foreach(var entrega in aux)
                {
                    entrega.MostraP = true;    
                }
            }
            var auxEstados = new List<FreteLocalInfo>();
            foreach (var entrega in ret)
            {
                foreach(var local in entrega.Locais)
                {
                    auxEstados.Add(local);   
                }
            }
            _filtro.Estados = auxEstados.GroupBy(x => x.Uf).Select(y => y.First()).Where(a => a.Uf != null).Select(z => z.Uf).ToList();
            return ret;
        }

        private void inicializarComponente()
        {
            _filtro = new FreteFiltroPage();

            _freteListView = new ListView()
            {
                Style = Estilo.Current[Estilo.LISTA_PADRAO]
            };
            _freteListView.HasUnevenRows = true;
            _freteListView.RowHeight = -1;
            _freteListView.SeparatorVisibility = SeparatorVisibility.Default;
            _freteListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _freteListView.ItemTemplate = new DataTemplate(typeof(FreteMotoristaOldCell));
            _freteListView.ItemTapped += async (sender, e) =>
            {
                if (e == null)
                    return;

                FreteInfo entrInfo = (FreteInfo)((ListView)sender).SelectedItem;

                Navigation.PushAsync(new FreteAvisoPage(entrInfo));

            };



        }

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

    }
}

