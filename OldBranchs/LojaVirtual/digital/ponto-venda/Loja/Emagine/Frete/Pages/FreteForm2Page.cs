using System;
using System.Collections.Generic;
using Emagine.Base.Estilo;
using Emagine.Frete.Cells;
using Emagine.Frete.Model;
using Emagine.Mapa.Model;
using Emagine.Mapa.Pages;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class FreteForm2Page : ContentPage
    {
        public List<PontoTransporteInfo> _ListaPontos;

        private FreteInfo _FreteInfo;
        private Button _EnviarButton;

        public FreteForm2Page(FreteInfo FreteInfo)
        {
            _FreteInfo = FreteInfo;
            Title = "Escolha a rota";
            inicializarComponente();
            Content = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Children = {
                    gerarPainelDestino(),
                    _EnviarButton
                }
            };
        }

        public void inicializarComponente(){
            _EnviarButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.End,
                Margin = new Thickness(8, 0),
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Continuar"
            };
            _EnviarButton.Clicked += (sender, e) => {
                continuaPedido();
            };
        }

        public void continuaPedido(){
            if (validaPedido())
            {
                if(_ListaPontos != null){
                    _FreteInfo.Locais = new List<FreteLocalInfo>();
                    var ordemAux = 0;
                    foreach(var ponto in _ListaPontos){
                        ordemAux++;
                        if (ponto.Tipo != TipoPontoTransporteEnum.Add)
                            _FreteInfo.Locais.Add(new FreteLocalInfo()
                            {
                                Tipo = getTipoValueByEnum(ponto.Tipo),
                                Latitude = ponto.Posicao.Value.Latitude,
                                Longitude = ponto.Posicao.Value.Longitude,
                                Ordem = ordemAux
                            });
                    }
                }
                Navigation.PushAsync(new FreteForm3Page(_FreteInfo));
            }
            else
            {
                DisplayAlert("Atenção", "Preencha pelo menos o local de saída e entrega do produto.", "Entendi");
            }
        }

        private bool validaPedido()
        {
            foreach (var ponto in _ListaPontos)
            {
                if (ponto.Tipo != TipoPontoTransporteEnum.Add)
                    if (ponto.Posicao == null)
                        return false;
            }
            return true;
        }

        private FreteLocalTipoEnum getTipoValueByEnum(TipoPontoTransporteEnum tipo){
            switch(tipo){
                case TipoPontoTransporteEnum.Carga:
                    return FreteLocalTipoEnum.Saida;
                case TipoPontoTransporteEnum.Destino:
                    return FreteLocalTipoEnum.Destino;
                case TipoPontoTransporteEnum.Trecho:
                    return FreteLocalTipoEnum.Parada;
                default:
                    return FreteLocalTipoEnum.Saida;
            }
        }

        private ListView gerarPainelDestino()
        {
            var ret = new ListView();
            ret.HasUnevenRows = true;
            ret.RowHeight = -1;
            ret.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            ret.ItemTemplate = new DataTemplate(typeof(PointTransporteCell));
            ret.SeparatorVisibility = SeparatorVisibility.None;
            _ListaPontos = new List<PontoTransporteInfo>()
            {
                new PontoTransporteInfo(){ Text = "", Tipo = TipoPontoTransporteEnum.Carga },
                new PontoTransporteInfo(){ Text = "", Tipo = TipoPontoTransporteEnum.Destino },
                new PontoTransporteInfo(){ Text = "", Tipo = TipoPontoTransporteEnum.Add }
            };
            ret.ItemsSource = _ListaPontos;
            ret.ItemTapped += (sender, e) =>
            {
                if (e == null)
                    return;
                var item = (PontoTransporteInfo)((ListView)sender).SelectedItem;
                Navigation.PushAsync(new MapaPageOld(item.Text, ref item, ref _ListaPontos, ref ret));
            };

            return ret;
        }
    }
}

