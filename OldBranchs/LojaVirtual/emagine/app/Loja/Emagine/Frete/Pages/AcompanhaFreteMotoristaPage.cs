using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Frete.BLL;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Utils;
using Emagine.Mapa.Controls;
using Emagine.Mapa.Utils;
using Plugin.Geolocator;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Pages
{
    [Obsolete("Use MapaAcompanhaPage")]
    public class AcompanhaFreteMotoristaPage : ContentPage
    {
        private CustomMap _CustomMap;
        private Button _AcaoButton;
        private Button _AbrirRota;
        private Button _ContatoButton;
        private Label _Distancia;
        private Label _Tempo;
        private int _IdFrete;
        private FreteSituacaoEnum _Situacao;
        private EventHandler<Plugin.Geolocator.Abstractions.PositionEventArgs> _PosicaoAlterada;
        private Picker _PickerRota;


        public AcompanhaFreteMotoristaPage(MotoristaRetornoInfo info)
        {
            _IdFrete = (int)info.IdFrete;
            _Situacao = (FreteSituacaoEnum)info.CodSituacao;
            inicializarComponente();
            Title = "Acompanhar entrega";
            Content = new StackLayout()
            {
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _CustomMap,
                    _Distancia,
                    _Tempo,
                    _ContatoButton,
                    _PickerRota,
                    _AbrirRota,
                    _AcaoButton
                }
            };
            if (_Situacao == FreteSituacaoEnum.Entregue)
            {
                ((StackLayout)Content).Children.Remove(_AcaoButton);
            }
            this._PosicaoAlterada += async (sender, e) =>
            {
                var retPedido = await new MotoristaBLL().listarPedidosAsync();
                apresentaInfo(retPedido, new Position(e.Position.Latitude, e.Position.Longitude));
            };
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            //var retPedido = await new MotoristaBLL().listarPedidosAsync();
            //await iniciaAsync(retPedido);
            MotoristaUtils.Avisando = true;
        }

        protected override void OnDisappearing()
        {
            //CrossGeolocator.Current.PositionChanged -= testeAsync;
            //CrossGeolocator.Current.StopListeningAsync().Wait();
            //AtualizacaoFrete.setConfirm(false);
            MotoristaUtils.Avisando = false;
            base.OnDisappearing();  
        }

        public async System.Threading.Tasks.Task iniciaAsync(MotoristaRetornoInfo info)
        {
            if (MapsUtils.IsLocationAvailable())
            {
                var ret = await CrossGeolocator.Current.GetLastKnownLocationAsync();
                apresentaInfo(info, new Position(ret.Latitude, ret.Longitude));
                if(CrossGeolocator.Current.IsListening)
                    await CrossGeolocator.Current.StopListeningAsync();
                await CrossGeolocator.Current.StartListeningAsync(new TimeSpan(5000), 20);
                CrossGeolocator.Current.PositionChanged += testeAsync;
            }
        }

        private async void testeAsync(object sender, Plugin.Geolocator.Abstractions.PositionEventArgs e)
        {
            if (e.Position != null)
            {
                if(_PosicaoAlterada != null)
                    _PosicaoAlterada(sender, e);    

            }
        }



        private void apresentaInfo(MotoristaRetornoInfo info, Position localizacaoAtual)
        {
            try
            { 
                _CustomMap.Polyline = MapaUtils.decodePolyline(info.Polyline);
                /*
                if (_CustomMap.RouteCoordinates == null)
                {
                    foreach (var ponto in pontos)
                    {
                        _CustomMap.RouteCoordinates.Add(ponto);
                    }
                }
                else
                {
                    _CustomMap.addPolyline(pontos);
                }
                */
                _CustomMap.MoveToRegion(MapSpan.FromCenterAndRadius(localizacaoAtual, Distance.FromMiles(0.1)));
                _Distancia.Text = "Distância até o destino: " + info.DistanciaStr;
                _Tempo.Text = "Tempo até o destino: " + info.TempoStr;
            } catch(Exception){
                   
            }
        }

        private async System.Threading.Tasks.Task confirmaPegaEntregaAsync()
        {
            UserDialogs.Instance.ShowLoading("Aguarde...");
            try{
                var infoEntrega = await FreteFactory.create().pegar(_IdFrete);
                if(_Situacao == FreteSituacaoEnum.PegandoEncomenda)
                    infoEntrega.Situacao = FreteSituacaoEnum.Entregando;
                else if(_Situacao == FreteSituacaoEnum.Entregando){
                    infoEntrega.Situacao = FreteSituacaoEnum.Entregue;
                }
                try{
                    await FreteFactory.create().alterar(infoEntrega);
                    _Situacao = infoEntrega.Situacao;
                    UserDialogs.Instance.HideLoading();
                    if (_Situacao == FreteSituacaoEnum.Entregando)
                        _AcaoButton.Text = "Entreguei a encomenda";
                    else if (_Situacao == FreteSituacaoEnum.Entregue)
                    {
                        await UserDialogs.Instance.AlertAsync("Obrigado, agora é só aguardar a confirmação de entrega.");
                        Navigation.PopToRootAsync();
                    }
                } catch(Exception e){
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert("Ocorreu um erro ao tentar alterar a situação da entrega", "Falha");
                }

            } catch(Exception e){
                UserDialogs.Instance.HideLoading();
            }
        }

        private async void mostraDadosEntrega(){
            UserDialogs.Instance.ShowLoading("Carregando...");
            var ret = await FreteFactory.create().pegar(_IdFrete);
            UserDialogs.Instance.HideLoading();
            UserDialogs.Instance.Alert(
                "Usuário: " + ret.Usuario.Nome +
                "\nTelefone: " + ret.Usuario.Telefone +
                "\nObservação: " + ret.Observacao,
                "Dados da entrega", "Fechar");
        } 

        private void inicializarComponente()
        {
            
            _AcaoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = (_Situacao == FreteSituacaoEnum.PegandoEncomenda ? "Peguei a encomenda" : "Entreguei a encomenda")
            };
            _AcaoButton.Clicked += (sender, e) => {
                confirmaPegaEntregaAsync();
            };

            _AbrirRota = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Ver rota externamente"
            };
            _AbrirRota.Clicked += (sender, e) => {
                _PickerRota.Focus();
            };

            _PickerRota = new Picker()
             {
                 ItemsSource = new List<string>(){
                        "Waze",
                        "Maps"
                    },
                 HeightRequest = 0,
                IsVisible = false
             };
            _PickerRota.SelectedIndexChanged += async (sender2, e2) =>
            {
                UserDialogs.Instance.ShowLoading("Aguarde...");
                var infoEntrega = await FreteFactory.create().pegar(_IdFrete);
                UserDialogs.Instance.HideLoading();
                switch ((string)_PickerRota.SelectedItem)
                {
                    case "Maps":
                        Device.OpenUri(new Uri("http://maps.google.com/maps?daddr=" + infoEntrega.EnderecoDestino));
                        break;
                    case "Waze":
                        Device.OpenUri(new Uri("waze://?q=" + infoEntrega.EnderecoDestino));
                        break;
                }
            };

            _ContatoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Ver dados do pedido"
            };
            _ContatoButton.Clicked += (sender, e) => {
                mostraDadosEntrega();
            };


            _Distancia = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                HeightRequest = 22,
                Margin = new Thickness(8, 0),
                FontSize = 18,
                TextColor = Color.Black
            };
            _Tempo = new Label()
            {
                VerticalOptions = LayoutOptions.End,
                HorizontalOptions = LayoutOptions.Fill,
                Margin = new Thickness(8, 0),
                HeightRequest = 22,
                FontSize = 18,
                TextColor = Color.Black
            };

            _CustomMap = new CustomMap
            {
                MapType = MapType.Street,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.Fill,
                IsShowingUser = true
            };

        }
    }
}

