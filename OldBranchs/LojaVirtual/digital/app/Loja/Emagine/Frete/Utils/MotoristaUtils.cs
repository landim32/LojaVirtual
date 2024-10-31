using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Base.Utils;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.GPS.Model;
using Emagine.GPS.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Frete.Utils
{
    public static class MotoristaUtils
    {
        private static bool _avisando = false;

        public static bool Avisando {
            get {
                return _avisando;
            }
            set
            {
                _avisando = value;
            }
        }

        public static async void inicializar(object sender, EventArgs e)
        {
            var regraMotorista = MotoristaFactory.create();
            var motorista = regraMotorista.pegarAtual();
            if (motorista != null) {
                PermissaoUtils.pedirPermissao();
                if (await GPSUtils.Current.inicializar()) {
                    GPSUtils.Current.aoAtualizarPosicao += atualizarPosicao;
                }
                else {
                    await UserDialogs.Instance.AlertAsync("Ative seu GPS!", "Erro", "Entendi");
                }
            }
        }

        private static void atualizarPosicao(object sender, GPSLocalInfo local)
        {
            Device.BeginInvokeOnMainThread(() => {
                atualizarPosicao(local);
            });
        }

        public static async void atualizarPosicao(GPSLocalInfo local)
        {
            if (_avisando) {
                return;
            }
            _avisando = true;
            try
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();

                if (motorista != null)
                {
                    var retorno = await regraMotorista.atualizar(new MotoristaEnvioInfo
                    {
                        IdMotorista = motorista.Id,
                        Latitude = local.Latitude,
                        Longitude = local.Longitude,
                        CodDisponibilidade = 1
                    });
                    if (retorno.IdFrete == null && retorno.Fretes != null && retorno.Fretes.Count > 0) {
                        var motoristaFrete = retorno.Fretes.First();
                        string mensagem = null;
                        if (motoristaFrete.Valor.HasValue)
                        {
                            mensagem = "Nova entrega no valor de R$ {0} disponível para iniciar.";
                            mensagem = string.Format(mensagem, motoristaFrete.Valor.Value.ToString("N2"));
                        }
                        else {
                            mensagem = "Nova entrega com valor a combinar disponível para iniciar.";
                        }
                        var confirmacao = await UserDialogs.Instance.ConfirmAsync(mensagem, "Entrega", "Ver", "Não quero");
                        var regraFrete = FreteFactory.create();
                        if (confirmacao)
                        {
                            UserDialogs.Instance.ShowLoading("Atualizando...");
                            try
                            {
                                var frete = await regraFrete.pegar(motoristaFrete.IdFrete);
                                UserDialogs.Instance.HideLoading();
                                //((RootPage)App.Current.MainPage).PushAsync(new ConfirmaEntregaPage(frete));
                            }
                            catch (Exception erro) {
                                UserDialogs.Instance.HideLoading();
                                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                            }
                        }
                        else
                        {
                            var aceite = await regraFrete.aceitar(new AceiteEnvioInfo {
                                Aceite = false,
                                IdFrete = motoristaFrete.IdFrete,
                                IdMotorista = motorista.Id,
                                Valor = motoristaFrete.Valor.HasValue ? motoristaFrete.Valor.Value : 0
                            });
                            if (aceite != null && !string.IsNullOrEmpty(aceite.Mensagem)) {
                                UserDialogs.Instance.Alert(aceite.Mensagem, "Erro", "Entendi");
                            }
                        }
                    }

                    /*
                    if (AcompanhaPageAtual != null)
                    {
                        var mapaRota = new MapaRotaInfo
                        {
                            Distancia = retorno.Distancia.HasValue ? retorno.Distancia.Value : 0,
                            DistanciaStr = retorno.DistanciaStr,
                            Tempo = retorno.Tempo.HasValue ? retorno.Tempo.Value : 0,
                            TempoStr = retorno.TempoStr,
                            PolylineStr = retorno.Polyline,
                            PosicaoAtual = new Mapa.Model.LocalInfo
                            {
                                Latitude = local.Latitude,
                                Longitude = local.Longitude
                            },
                            Polyline = MapaUtils.decodePolyline(retorno.Polyline)
                        };
                        if (string.IsNullOrEmpty(retorno.Polyline) && retorno.IdFrete.HasValue)
                        {
                            var regraFrete = FreteFactory.create();
                            var frete = await regraFrete.pegar(retorno.IdFrete.Value);
                            mapaRota.Polyline = new List<Position>();
                            foreach (var freteLocal in frete.Locais)
                            {
                                mapaRota.Polyline.Add(new Position(freteLocal.Latitude, freteLocal.Longitude));
                            }
                        }
                        if (AcompanhaPageAtual != null)
                        {
                            AcompanhaPageAtual.atualizarMapa(mapaRota);
                        }
                    }
                    */
                }
                _avisando = false;
            }
            catch (Exception erro)
            {
                _avisando = false;
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
            }
        }
    }
}
