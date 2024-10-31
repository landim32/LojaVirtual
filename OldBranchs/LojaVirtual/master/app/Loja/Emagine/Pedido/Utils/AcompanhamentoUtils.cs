using Acr.UserDialogs;
using Emagine.Frete.Pages;
using Emagine.GPS.Model;
using Emagine.GPS.Utils;
using Emagine.Mapa.Model;
using Emagine.Mapa.Utils;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Model;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Pedido.Utils
{
    public static class AcompanhamentoUtils
    {
        public static MapaAcompanhaPage AcompanhaPageAtual { get; set; }

        public static void iniciarAcompanhamento(PedidoInfo pedido)
        {
            //if (await GPSUtils.Current.inicializar()) {
            if (GPSUtils.Current.estaAtivo()) {
                var regraPedido = PedidoFactory.create();
                regraPedido.gravarAtual(pedido);
                GPSUtils.Current.aoAtualizarPosicao += atualizarPosicao;
            }
            else {
                UserDialogs.Instance.Alert("Ative seu GPS!", "Erro", "Entendi");
            }
        }

        public static async void finalizarAcompanhamento()
        {
            var regraPedido = PedidoFactory.create();
            await regraPedido.limparAtual();
            AcompanhaPageAtual = null;
            GPSUtils.Current.aoAtualizarPosicao -= atualizarPosicao;
            await GPSUtils.Current.finalizar();
        }

        private static void atualizarPosicao(object sender, GPSLocalInfo local)
        {
            Device.BeginInvokeOnMainThread(async () => {
                try
                {
                    var regraPedido = PedidoFactory.create();
                    var pedido = regraPedido.pegarAtual();
                    if (pedido == null)
                    {
                        return;
                    }
                    var envio = new PedidoEnvioInfo
                    {
                        IdPedido = pedido.Id,
                        Latitude = local.Latitude,
                        Longitude = local.Longitude
                    };
                    var retorno = await regraPedido.atualizar(envio);
                    if (!string.IsNullOrEmpty(retorno.Polyline))
                    {
                        if (AcompanhaPageAtual != null)
                        {
                            var rota = new MapaRotaInfo
                            {
                                PosicaoAtual = new LocalInfo
                                {
                                    Latitude = retorno.Latitude,
                                    Longitude = retorno.Longitude
                                },
                                PolylineStr = retorno.Polyline,
                                Distancia = retorno.Distancia,
                                DistanciaStr = retorno.DistanciaStr,
                                Tempo = retorno.Tempo,
                                TempoStr = retorno.TempoStr
                            };
                            rota.Polyline = MapaUtils.decodePolyline(rota.PolylineStr);
                            if (AcompanhaPageAtual != null)
                            {
                                AcompanhaPageAtual.atualizarMapa(rota);
                            }
                        }
                    }
                    else if (!string.IsNullOrEmpty(retorno.Mensagem))
                    {
                        UserDialogs.Instance.Alert(retorno.Mensagem, "Erro", "Entendi");
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                }
            });
        }

        public static async Task<MapaAcompanhaPage> gerarMapaAcompanhamento(PedidoInfo pedido)
        {
            if (!GPSUtils.UsaLocalizacao)
            {
                await App.Current.MainPage.DisplayAlert("Erro", "App não configurado para usar GPS.", "Entendi");
                return null;
            }
            if (!await GPSUtils.Current.inicializar())
            {
                await App.Current.MainPage.DisplayAlert("Erro", "Ative seu GPS.", "Entendi");
                return null;
            }
            var mapaAcompanha = new MapaAcompanhaPage
            {
                Title = "Acompanhar",
            };
            mapaAcompanha.Appearing += (sender, e) => {
                AcompanhaPageAtual = mapaAcompanha;
            };
            mapaAcompanha.Disappearing += (sender, e) => {
                AcompanhaPageAtual = null;
            };
            return mapaAcompanha;
        }
    }
}
