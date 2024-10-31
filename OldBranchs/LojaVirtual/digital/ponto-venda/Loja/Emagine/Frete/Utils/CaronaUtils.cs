using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Frete.Pages;
using Emagine.GPS.Model;
using Emagine.GPS.Utils;
using Emagine.Login.Factory;
using Emagine.Mapa.Model;
using Emagine.Mapa.Pages;
using Emagine.Mapa.Utils;
using Frete.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xamarin.Forms.Maps;

namespace Emagine.Frete.Utils
{
    public static class CaronaUtils
    {
        public static MapaAcompanhaPage AcompanhaPageAtual { get; set; }
        public static bool Acompanhando { get; set; } = false;

        public async static void inicializar() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario == null) {
                return;
            }
            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(usuario.Id, usuario.Id);
                if (fretes.Count() > 0)
                {
                    var frete = regraFrete.pegarAtual();
                    var situacoes = new List<FreteSituacaoEnum>() {
                        FreteSituacaoEnum.PegandoEncomenda,
                        FreteSituacaoEnum.Entregando
                    };
                    var freteAtual = (
                        from f in fretes
                        where (frete == null || f.Id == frete.Id) && situacoes.Contains(f.Situacao)
                        select f
                    ).FirstOrDefault();
                    if (freteAtual != null)
                    {
                        regraFrete.gravarAtual(freteAtual);
                    }
                    else {
                        regraFrete.limparAtual();
                    }
                }
                else {
                    regraFrete.limparAtual();
                }
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                var freteInfo = regraFrete.pegarAtual();
                if (motorista != null)
                {
                    if (motorista.Situacao == MotoristaSituacaoEnum.AguardandoAprovacao)
                    {
                        UserDialogs.Instance.Alert("Conta de motorista aguardando aprovação!", "Aviso", "Entendi");
                    }
                    else {
                        if (freteInfo != null)
                        {
                            CaronaUtils.acompanharComoMotorista(freteInfo);
                        }
                        else
                        {
                            CaronaUtils.buscarFreteComoMotorista(false);
                        }
                    }
                }
                else {
                    if (freteInfo != null)
                    {
                        CaronaUtils.acompanharComoCliente(freteInfo);
                    }
                    else
                    {
                        CaronaUtils.criar();
                    }
                }
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static void acompanharComoMotorista(FreteInfo frete) {
            var regraFrete = FreteFactory.create();
            regraFrete.gravarAtual(frete);

            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando
            };

            var acompanhaPage = new MapaAcompanhaPage {
                Title = "Acompanhar viagem",
                Duracao = frete.Duracao,
                DuracaoVisivel = situacoes.Contains(frete.Situacao)
            };
            acompanhaPage.Appearing += (sender, e) => {
                CaronaUtils.AcompanhaPageAtual = (MapaAcompanhaPage)sender;
            };
            acompanhaPage.Disappearing += (sender, e) => {
                CaronaUtils.AcompanhaPageAtual = null;
            };
            ((RootPage)App.Current.MainPage).PushAsync(acompanhaPage);
        }

        public static void acompanharComoCliente(FreteInfo frete)
        {
            var regraFrete = FreteFactory.create();
            regraFrete.gravarAtual(frete);

            var situacoes = new List<FreteSituacaoEnum>() {
                FreteSituacaoEnum.PegandoEncomenda,
                FreteSituacaoEnum.Entregando
            };

            var acompanhaPage = new MapaAcompanhaPage
            {
                Title = "Acompanhar viagem",
                Duracao = frete.Duracao,
                DuracaoVisivel = situacoes.Contains(frete.Situacao)
            };
            acompanhaPage.Appearing += acompanhaAppearing;
            acompanhaPage.Disappearing += (sender, e) => {
                CaronaUtils.Acompanhando = false;
            };
            ((RootPage)App.Current.MainPage).PushAsync(acompanhaPage);
        }

        private static async void atualizarMapa(MapaAcompanhaPage acompanhaPage, FreteInfo frete) {
            var regraFrete = FreteFactory.create();
            var retorno = await regraFrete.atualizar(frete.Id);
            if (retorno != null)
            {
                Device.BeginInvokeOnMainThread(() =>
                {
                    var mapaRota = new MapaRotaInfo
                    {
                        Distancia = retorno.Distancia,
                        DistanciaStr = retorno.DistanciaStr,
                        Tempo = retorno.Tempo,
                        TempoStr = retorno.TempoStr,
                        PolylineStr = retorno.Polyline,
                        PosicaoAtual = new Mapa.Model.LocalInfo
                        {
                            Latitude = retorno.Latitude,
                            Longitude = retorno.Longitude
                        },
                        Polyline = MapaUtils.decodePolyline(retorno.Polyline)
                    };
                    if (string.IsNullOrEmpty(retorno.Polyline)) {
                        mapaRota.Polyline = new List<Position>();
                        foreach (var local in frete.Locais) {
                            mapaRota.Polyline.Add(new Position(local.Latitude, local.Longitude));
                        }
                    }
                    acompanhaPage.atualizarMapa(mapaRota);
                });
            }
        }

        private static bool executarAcompanhamento(MapaAcompanhaPage acompanhaPage) {
            var regraFrete = FreteFactory.create();
            var frete = regraFrete.pegarAtual();
            if (frete == null) {
                return false;
            }
            atualizarMapa(acompanhaPage, frete);
            return CaronaUtils.Acompanhando;
        }

        private static void acompanhaAppearing(object sender, EventArgs e)
        {
            CaronaUtils.Acompanhando = true;
            var acompanhaPage = (MapaAcompanhaPage)sender;
            if (executarAcompanhamento(acompanhaPage)) {
                Device.StartTimer(new TimeSpan(0, 0, GPSUtils.Current.TempoMinimo), () => {
                    return executarAcompanhamento(acompanhaPage);
                });
            }
        }

        public static async void atualizarPosicao(GPSLocalInfo local) {
            try
            {
                //var regraFrete = FreteFactory.create();
                //var frete = regraFrete.pegarAtual();
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();

                //if (motorista != null && frete != null)
                if (motorista != null)
                {
                    var retorno = await regraMotorista.atualizar(new MotoristaEnvioInfo
                    {
                        IdMotorista = motorista.Id,
                        Latitude = local.Latitude,
                        Longitude = local.Longitude,
                        CodDisponibilidade = 1
                    });
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
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
            }
        }

        public static void criar() {
            var mapaPage = new MapaRotaPage
            {
                Title = "Selecione a rota",
                TituloPadrao = "Parte do trageto",
                IniciarEmPosicaoAtual = true
            };
            mapaPage.AoSelecionar += (sender, posicoes) => {
                var frete = new FreteInfo();
                foreach (var posicao in posicoes) {
                    frete.Locais.Add(new FreteLocalInfo
                    {
                        Tipo = FreteLocalTipoEnum.Parada,
                        Latitude = posicao.Latitude,
                        Longitude = posicao.Longitude
                    });
                }
                var caronaPage = new CaronaFormPage
                {
                    Title = "Nova carona",
                    Frete = frete
                };
                caronaPage.AoCadastrar += async (s2, f) => {
                    await ((Page)sender).Navigation.PushAsync(new FretePage
                    {
                        Title = frete.SituacaoStr,
                        Frete = f
                    });
                };
                ((RootPage)App.Current.MainPage).PushAsync(caronaPage);
            };
            ((RootPage)App.Current.MainPage).PushAsync(mapaPage);
        }

        public static async void listarMeuFreteComoCliente() {

            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(usuario.Id);
                var freteListaPage = new FreteListaPage {
                    Title = "Meus atendimentos",
                    Fretes = fretes,
                    FiltroBotao = false,
                    NovoBotao = false
                };
                UserDialogs.Instance.HideLoading();
                ((RootPage)App.Current.MainPage).PushAsync(freteListaPage);
            }
            catch (Exception e) {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static async void listarMeuFreteComoMotorista()
        {

            UserDialogs.Instance.ShowLoading("carregando...");
            try
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(0, motorista.Id);
                var freteListaPage = new FreteListaPage
                {
                    Title = "Meus atendimentos",
                    Fretes = fretes,
                    FiltroBotao = false,
                    NovoBotao = false
                };
                UserDialogs.Instance.HideLoading();
                ((RootPage)App.Current.MainPage).PushAsync(freteListaPage);
            }
            catch (Exception e)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }

        public static async void buscarFreteComoMotorista(bool carregando = true)
        {
            if (carregando) {
                UserDialogs.Instance.ShowLoading("carregando...");
            }
            try
            {
                var regraMotorista = MotoristaFactory.create();
                var motorista = regraMotorista.pegarAtual();
                var regraFrete = FreteFactory.create();
                var fretes = await regraFrete.listar(0, 0, FreteSituacaoEnum.ProcurandoMotorista);
                var freteListaPage = new FreteListaPage
                {
                    Title = "Buscar atendimentos",
                    Fretes = fretes,
                    FiltroBotao = false,
                    NovoBotao = false
                };
                if (carregando) {
                    UserDialogs.Instance.HideLoading();
                }
                ((RootPage)App.Current.MainPage).PushAsync(freteListaPage);
            }
            catch (Exception e)
            {
                if (carregando) {
                    UserDialogs.Instance.HideLoading();
                }
                await UserDialogs.Instance.AlertAsync(e.Message, "Erro", "Entendi");
            }
        }
    }
}
