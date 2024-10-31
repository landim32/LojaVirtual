using Radar.DALFactory;
using Radar.IDAL;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Radar.Factory;
using Emagine.Utils;
using System.Collections.ObjectModel;

namespace Radar.BLL
{
    public class PercursoBLL
    {
        private IPercursoDAL _percursoDB;
        private IPercursoPontoDAL _pontoDB;

        private const int TEMPO_MINIMO_PARADO = 120;
        private const int TEMPO_PARADA_PERCURSO = 7200; // 2 horas
        private const int DISTANCIA_MINIMA_RESUMO = 100; // metros
        private const int DISTANCIA_MINIMA_PROCESSAMENTO = 15; // metros
        private const int VELOCIDADE_MAXIMA_PARADO = 3;
        public const int NOTIFICACAO_GRAVAR_PERCURSO_ID = 2301;
        public const int NOTIFICACAO_PARAR_PERCURSO_ID = 2302;
        public const int NOTIFICACAO_SIMULACAO_PERCURSO_ID = 1034;
        public const int NOTIFICACAO_SIMULACAO_PARAR_PERCURSO_ID = 1035;
        public const string ACAO_PARAR_SIMULACAO = "parar-simulacao";
        public const string ACAO_PARAR_GRAVACAO = "parar-gravacao";

        public PercursoBLL()
        {
            _percursoDB = PercursoDALFactory.create();
            _pontoDB = PercursoPontoDALFactory.create();
        }

        private void atualizar(PercursoInfo percurso) {
            if (percurso != null)
            {
                var pontos = _pontoDB.listar(percurso.Id);
                percurso.Pontos = pontos;
            }
        }
        
          public IList<PercursoInfo> listar() {
            IList<PercursoInfo> percursos = _percursoDB.listar();
            foreach (PercursoInfo percurso in percursos) {
                atualizar(percurso);
            }
            return percursos;
        }

        public PercursoInfo pegar(int id) {
            PercursoInfo percurso = _percursoDB.pegar(id);
            atualizar(percurso);
            return percurso;
        }

        public int gravar(PercursoInfo percurso) {
			var grava = _percursoDB.gravar(percurso);
            return grava;
        }

        public int gravarPonto(PercursoPontoInfo ponto) {			
            return _pontoDB.gravar(ponto);
        }

        public void atualizarEndereco(PercursoInfo percurso)
        {
            if (!InternetUtils.estarConectado()) return;
            if (percurso.Pontos.Count == 0) return;

            var ponto = percurso.Pontos.Last();
            GeocoderUtils.pegarAsync((float)ponto.Latitude, (float)ponto.Longitude, (sender, e) =>
            {
                var endereco = e.Endereco;
                percurso.Endereco = endereco.Logradouro + " " + endereco.Complemento + " " + endereco.Bairro + " " + endereco.Cidade + " " + endereco.Uf + " " + endereco.CEP;
                gravar(percurso);
            });
        }

		public void atualizarEndereco()
		{
			if (InternetUtils.estarConectado())
			{
				var percursos = _percursoDB.listarEnderecoNulo();
				if (percursos.Count > 0)
				{
					int idPercurso = percursos[0].Id;
					var pontos = _pontoDB.listar(idPercurso);
					if (pontos.Count() > 0)
					{
						float lat = (float)(from p in pontos select p.Latitude).Last();
						float lon = (float)(from p in pontos select p.Longitude).Last();

						GeocoderUtils.pegarAsync(lat, lon, (sender, e) =>
						{
							var endereco = e.Endereco;
							PercursoInfo percurso = new PercursoInfo()
							{
								Id = idPercurso,
								Endereco = endereco.Logradouro + " " + endereco.Complemento + " " + endereco.Bairro + " " + endereco.Cidade + " " + endereco.Uf + " " + endereco.CEP
							};
							gravar(percurso);
							atualizarEndereco();
						});
					}

				}
			}
		}

        public bool iniciarGravacao() {
            if (PercursoUtils.Gravando)
                return false;
			PercursoInfo percurso = new PercursoInfo();
            gravar(percurso);
            PercursoUtils.PercursoAtual = percurso;
            PercursoUtils.Gravando = true;
            PercursoUtils.Latitude = 0;
            PercursoUtils.Longitude = 0;
            MensagemUtils.avisar("Iniciando gravação do percurso!");
            MensagemUtils.notificarPermanente(
                NOTIFICACAO_GRAVAR_PERCURSO_ID,
                "Gravando Percurso...", "",
                NOTIFICACAO_PARAR_PERCURSO_ID,
                "Parar", ACAO_PARAR_GRAVACAO
            );
            return true;
        }

        public bool pararGravacao()
        {
            if (!PercursoUtils.Gravando)
                return false;
            var percurso = PercursoUtils.PercursoAtual;
            PercursoUtils.PercursoAtual = null;
            PercursoUtils.Gravando = false;
            MensagemUtils.avisar("Gravação finalizada!");
            MensagemUtils.pararNotificaoPermanente(PercursoBLL.NOTIFICACAO_GRAVAR_PERCURSO_ID);
            atualizarEndereco(percurso);
            return true;
        }

        private PercursoPontoInfo gerarPonto(LocalizacaoInfo local, RadarInfo radar = null) {
            return new PercursoPontoInfo()
            {
                IdPercurso = PercursoUtils.PercursoAtual.Id,
                Latitude = local.Latitude,
                Longitude = local.Longitude,
                Velocidade = local.Velocidade,
                Sentido = local.Sentido,
                Precisao = local.Precisao,
                Data = local.Tempo,
                IdRadar = (radar != null) ? radar.Id : 0
            };
        }

        private void processarPonto(LocalizacaoInfo local, RadarInfo radar = null)
        {
            var distancia = GPSUtils.calcularDistancia(local.Latitude, local.Longitude, PercursoUtils.Latitude, PercursoUtils.Longitude);
            bool alterado = false;
            if (distancia >= DISTANCIA_MINIMA_PROCESSAMENTO)
            {
                var ponto = gerarPonto(local, radar);
                gravarPonto(ponto);
                PercursoUtils.PercursoAtual.Pontos.Add(ponto);

                PercursoUtils.Latitude = (float)local.Latitude;
                PercursoUtils.Longitude = (float)local.Longitude;
                alterado = true;
            }
            if (PercursoUtils.PaginaAtual != null) {
                PercursoUtils.PaginaAtual.atualizarGravacao(local, alterado);
            }
        }

        public bool executarGravacao(LocalizacaoInfo local, RadarInfo radar = null)
        {
            if (!PercursoUtils.Gravando)
                return false;
            if (local.Precisao > 15)
                return false;
            processarPonto(local, radar);
            return false;
        }

        public void excluir(int id)
        {
            PercursoInfo percurso = _percursoDB.pegar(id);
			if(percurso != null)
            	foreach (PercursoPontoInfo ponto in percurso.Pontos)
                	_pontoDB.excluir(ponto.Id);
            _percursoDB.excluir(id);
        }

		public TimeSpan tempoParado(PercursoInfo percurso) {
			TimeSpan total = new TimeSpan();
			if (percurso.Pontos.Count > 0) {
				DateTime maiorTempo = (from p in percurso.Pontos select p.Data).Max();
                DateTime menorTempo = (from p in percurso.Pontos select p.Data).Min();
				total = maiorTempo.Subtract(menorTempo);
			}
			return total;
		}

        public IList<PercursoResumoInfo> listarResumo(int idPercuso) {

            var regraRadar = RadarFactory.create();

            var percurso = pegar(idPercuso);
            var pontos = percurso.Pontos.ToList();

            var resumos = new List<PercursoResumoInfo>();

            var inicio = pontos[0];
            var chegada = pontos[pontos.Count - 1];
            pontos.Remove(inicio);
            pontos.Remove(chegada);

            resumos.Add(new PercursoResumoInfo {
                Icone = "partida.png",
                Descricao = "Saída",
                Data = inicio.Data,
                Distancia = 0,
                Latitude = (float)inicio.Latitude,
                Longitude = (float)inicio.Longitude,
                Tempo = TimeSpan.Zero
            });

            var idRadarOld = inicio.IdRadar;
            var dataOld = inicio.Data;
            var latitudeOld = (float)inicio.Latitude;
            var longitudeOld = (float)inicio.Longitude;
            double distanciaAcumulada = 0;
            TimeSpan tempoAcumulado = TimeSpan.Zero;

            double distancia = 0;
            TimeSpan tempo = TimeSpan.Zero;

            foreach (var ponto in percurso.Pontos) {
                distancia = GPSUtils.calcularDistancia(latitudeOld, longitudeOld, ponto.Latitude, ponto.Longitude);
                tempo = ponto.Data.Subtract(dataOld);
                if (distancia > DISTANCIA_MINIMA_RESUMO)
                {
                    distanciaAcumulada += distancia;
                    tempoAcumulado = tempoAcumulado.Add(tempo);
                    if (tempo.TotalSeconds > TEMPO_MINIMO_PARADO)
                    {
                        resumos.Add(new PercursoParadoInfo
                        {
                            Icone = "para.png",
                            Descricao = "Parada",
                            Data = ponto.Data,
                            Tempo = tempoAcumulado,
                            Distancia = distanciaAcumulada,
                            Latitude = (float)ponto.Latitude,
                            Longitude = (float)ponto.Longitude,
                        });
                        distanciaAcumulada = 0;
                        tempoAcumulado = TimeSpan.Zero;
                    }
                    if (idRadarOld != ponto.IdRadar && ponto.IdRadar > 0)
                    {
                        var ultimoPonto = (
                            from p in percurso.Pontos
                            where p.IdRadar == ponto.IdRadar
                            orderby p.Data descending
                            select p
                        ).FirstOrDefault<PercursoPontoInfo>();
                        var radar = regraRadar.pegar(ponto.IdRadar);
                        if (radar != null)
                        {
                            distancia = GPSUtils.calcularDistancia(latitudeOld, longitudeOld, ultimoPonto.Latitude, ultimoPonto.Longitude);
                            distanciaAcumulada += distancia;
                            resumos.Add(new PercursoRadarInfo
                            {
                                Icone = radar.Imagem,
                                Descricao = radar.Titulo,
                                Data = ultimoPonto.Data,
                                Distancia = distanciaAcumulada,
                                Latitude = (float)ultimoPonto.Latitude,
                                Longitude = (float)ultimoPonto.Longitude,
                                Tempo = tempoAcumulado,
                                MinhaVelocidade = ultimoPonto.Velocidade,
                                Velocidade = radar.Velocidade,
                                Tipo = radar.Tipo
                            });
                        }
                        idRadarOld = ponto.IdRadar;
                        distanciaAcumulada = 0;
                        tempoAcumulado = TimeSpan.Zero;
                    }

                    dataOld = ponto.Data;
                    latitudeOld = (float)ponto.Latitude;
                    longitudeOld = (float)ponto.Longitude;
                }
            }

            distancia = GPSUtils.calcularDistancia(latitudeOld, longitudeOld, chegada.Latitude, chegada.Longitude);
            tempo = chegada.Data.Subtract(dataOld);
            distanciaAcumulada += distancia;
            tempoAcumulado = tempoAcumulado.Add(tempo);
            resumos.Add(new PercursoResumoInfo
            {
                Icone = "chegada.png",
                Descricao = "Chegada",
                Data = chegada.Data,
                Tempo = tempoAcumulado,
                Distancia = distanciaAcumulada,
                Latitude = (float)chegada.Latitude,
                Longitude = (float)chegada.Longitude,
            });

            var regraGasto = GastoFactory.create();
            var gastos = regraGasto.listar(idPercuso);

            foreach (var gasto in gastos) {
                resumos.Add(new PercursoGastoInfo
                {
                    Icone = "ic_monetization_on_black_24dp.png",
                    Descricao = gasto.Observacao,
                    Data = gasto.DataInclusao,
                    Tempo = TimeSpan.Zero,
                    Distancia = 0,
                    Latitude = (float)gasto.Latitude,
                    Longitude = (float)gasto.Longitude,
                    FotoBase64 = gasto.FotoBase64
                });
            }

            var retorno = (
                from r in resumos 
                orderby r.Data
                select r
            ).ToList();
            return retorno;
        }

        /// <summary>
        /// Rodrigo Landim - 22/12
        /// FIZ ESSA PORRA DESSE JEITO PRA APROVEITAR O ResumoPercursoPage.cs JÁ CRIADO.
        /// A CULPA É DO CARLOS.
        /// </summary>
        /// <returns></returns>
        public ObservableCollection<ResumoInfo> converterParaRotinaEscrotaDoCarlos(IList<PercursoResumoInfo> resumos) {
            var retorno = new ObservableCollection<ResumoInfo>();
            foreach (var item in resumos)
            {
                var resumo = new ResumoInfo
                {
                    Imagem = item.Icone,
                    Nome = item.Descricao
                };

                if (item.Distancia > 0)
                {
                    resumo.Items.Add(new ResumoItemInfo
                    {
                        Descricao = "Distância",
                        Valor = (item.Distancia / 1000).ToString("N1") + " Km"
                    });
                }
                resumo.Items.Add(new ResumoItemInfo
                {
                    Descricao = "Latitude",
                    Valor = item.Latitude.ToString()
                });
                resumo.Items.Add(new ResumoItemInfo
                {
                    Descricao = "Longitude",
                    Valor = item.Longitude.ToString()
                });
                resumo.Items.Add(new ResumoItemInfo
                {
                    Descricao = "Data",
                    Valor = item.Data.ToString("dd / MMM")
                });
                if (item.Tempo.TotalSeconds > 0)
                {
                    resumo.Items.Add(new ResumoItemInfo
                    {
                        Descricao = "Tempo",
                        Valor = string.Format("{0:D2}:{1:D2}:{2:D2}", item.Tempo.Hours, item.Tempo.Minutes, item.Tempo.Seconds)
                    });
                }

                if (item is PercursoRadarInfo)
                {
                    var radar = (PercursoRadarInfo)item;
                    resumo.Items.Add(new ResumoItemInfo
                    {
                        Descricao = "Velocidade",
                        Valor = radar.Velocidade.ToString("N0") + " Km/h"
                    });
                    resumo.Items.Add(new ResumoItemInfo
                    {
                        Descricao = "Minha Vel.",
                        Valor = radar.MinhaVelocidade.ToString("N0") + " Km/h"
                    });
                }
                if (item is PercursoGastoInfo)
                {
                    resumo.Items.Add(new ResumoItemInfo
                    {
                        Descricao = "Foto",
                        FotoBase64 = ((PercursoGastoInfo)item).FotoBase64
                    });
                }
                retorno.Add(resumo);
            }
            return retorno;
        }
    }
}
