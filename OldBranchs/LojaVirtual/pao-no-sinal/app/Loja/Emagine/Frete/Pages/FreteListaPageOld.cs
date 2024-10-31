using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Acr.UserDialogs;
using Emagine.Frete.Model;
using Emagine.Frete.BLL;
using Emagine.Frete.Cells;
using Emagine.Login.BLL;
using Emagine.Frete.Factory;

namespace Emagine.Frete.Pages
{
    public class FreteListaPageOld : ContentPage
    {
        private ListView _freteListView;
        private bool _Historico;
        private bool _Inicio = true;

        public FreteListaPageOld(bool historico)
        {
            Title = "Meus Pedidos";
            inicializarComponente();
            _Historico = historico;
            Content = _freteListView;
        }

        public async Task<List<FreteInfo>> listarFreteAsync()
        {
            var ret = await FreteFactory.create().listar();
            if(_Historico){
                ret = ret.Where(x => x.Situacao == FreteSituacaoEnum.Cancelado || x.Situacao == FreteSituacaoEnum.EntregaConfirmada).ToList();
            } else {
                ret = ret.Where(x => x.Situacao != FreteSituacaoEnum.Cancelado && x.Situacao != FreteSituacaoEnum.EntregaConfirmada).ToList();

            }
            return ret;
        }

        private void inicializarComponente()
        {
            _freteListView = new ListView();
            _freteListView.HasUnevenRows = true;
            _freteListView.RowHeight = -1;
            _freteListView.SeparatorVisibility = SeparatorVisibility.None;
            _freteListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _freteListView.ItemTemplate = new DataTemplate(typeof(FreteCell));
            _freteListView.ItemTapped += async (sender, e) =>
            {
                if (e == null || _Historico)
                    return;

                FreteInfo entrInfo = (FreteInfo)((ListView)sender).SelectedItem;
                if(entrInfo.Situacao == FreteSituacaoEnum.PegandoEncomenda 
                   || entrInfo.Situacao == FreteSituacaoEnum.Entregando
                   || entrInfo.Situacao == FreteSituacaoEnum.Entregue){
                    
                    var retEntr = await FreteFactory.create().atualizar(entrInfo.Id);
                    if (retEntr.IdMotorista == new UsuarioBLL().pegarAtual().Id){
                        var retMot = await new MotoristaBLL().listarPedidosAsync();
                        Navigation.PushAsync(new AcompanhaFreteMotoristaPage(retMot));   
                    }
                    else{
                        Navigation.PushAsync(new AcompanhaFretePage(retEntr));
                    }
                        
                }


            };

           
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if(_Inicio){
                _Inicio = false;
                UserDialogs.Instance.ShowLoading("carregando...");
                _freteListView.ItemsSource = await listarFreteAsync();
                UserDialogs.Instance.HideLoading();
            }
        }
    }
}
