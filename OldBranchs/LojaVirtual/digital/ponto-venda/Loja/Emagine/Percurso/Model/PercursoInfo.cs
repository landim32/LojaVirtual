using SQLite;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Radar.BLL;
using Radar.Utils;

namespace Radar.Model
{
    [Table("percurso")]
    public class PercursoInfo
    {
        private IList<PercursoPontoInfo> _pontos = new List<PercursoPontoInfo>();

		[AutoIncrement, PrimaryKey]
        public int Id { get; set; }
		public string Endereco { get; set; }

        [Ignore]
        public IList<PercursoPontoInfo> Pontos {
            get {
                return _pontos;
            }
            set {
                _pontos = value;
            }
        }

        [Ignore]
        public string Titulo
        {
            get
            {
                if (_pontos.Count > 0)
                {
                    DateTime dataTitulo = _pontos[0].Data;
                    //return dataTitulo.ToString("dd/MMM - HH:mm");
                    return dataTitulo.AddHours(-2).ToString("dd/MMM - HH:mm");
                }
                return "Indefinido";
            }
        }

        [Ignore]
		public double DistanciaTotal {
            get {
                int count = 0;
                double total = 0;
                double initialLat = 0;
                double initialLong = 0;
                double finalLat = 0;
                double finalLong = 0;
                foreach (var pontos in _pontos)
                {
                    initialLat = pontos.Latitude;
                    initialLong = pontos.Longitude;
                    if (count > 0)
                    {
                        total += GPSUtils.calcularDistancia(initialLat, initialLong, finalLat, finalLong);
                    }
                    finalLat = pontos.Latitude;
                    finalLong = pontos.Longitude;
                    count++;
                }
                return total;
            }
        }

		[Ignore]
        public int VelocidadeMedia
        {
            get
            {
                double horas = TempoGravacao.TotalHours;
                return (int)Math.Floor(((DistanciaTotal / 1000) / horas));
            }
        }

		[Ignore]
		public int VelocidadeMaxima {
            get {
                if (_pontos.Count > 0)
                {
                    return (int)Math.Floor((from p in _pontos select p.Velocidade).Max());
                }
                return 0;
            }
        }

		[Ignore]
		public TimeSpan TempoParado {
            get {
                double segundos = 0;
                PercursoPontoInfo ultimoPonto = null;
                foreach (var ponto in _pontos) {
                    if (ultimoPonto != null) {
                        var tempo = ponto.Data.Subtract(ultimoPonto.Data);
                        if (tempo.TotalSeconds > 120) {
                            segundos += tempo.TotalSeconds;
                        }
                    }
                    ultimoPonto = ponto;
                }
                return new TimeSpan(0, 0, (int)Math.Floor(segundos));
            }
        }

		[Ignore]
		public int QuantidadeRadar {
            get {
                int quantidade = 0;
                if (_pontos.Count > 0)
                {
                    quantidade = (from p in _pontos where (p.IdRadar != 0) select p.IdRadar).Distinct().Count();
                }
                return quantidade;
            }
        }

		[Ignore]
		public int QuantidadeParada {
            get {
                int paradas = 0;
                PercursoPontoInfo ultimoPonto = null;
                foreach (var ponto in _pontos) {
                    if (ultimoPonto != null) {
                        var tempo = ponto.Data.Subtract(ultimoPonto.Data);
                        if (tempo.TotalSeconds > 120) {
                            paradas++;
                        }
                    }
                    ultimoPonto = ponto;
                }
                return paradas;
            }
        }

        [Ignore]
        public TimeSpan TempoGravacao {
            get {
                if (_pontos.Count >= 2) {
                    return _pontos[_pontos.Count - 1].Data.Subtract(_pontos[0].Data);
                }
                return TimeSpan.Zero;
            }
        }

        [Ignore]
        public string TempoGravacaoStr
        {
            get
            {
                return TempoGravacao.ToString(@"hh\:mm\:ss");
            }
        }

        [Ignore]
        public string VelocidadeMediaStr
		{
			get
			{
				return VelocidadeMedia.ToString()+ " Km/h ";
			}
		}

        [Ignore]
        public string VelocidadeMaximaStr
		{
			get
			{
				return VelocidadeMaxima.ToString() + " Km/h ";
			}
		}

        [Ignore]
        public string TempoParadoStr
		{
			get
			{
				return TempoParado.ToString(@"hh\:mm\:ss");
			}
		}

        [Ignore]
        public string QuantidadeRadarStr
		{
			get
			{
				return QuantidadeRadar.ToString();
			}
		}

        [Ignore]
        public string QuantidadeParadaStr
		{
			get
			{
				return QuantidadeParada.ToString();
			}
		}

        [Ignore]
        public string DistanciaTotalStr
		{
			get
			{
				double distanciaTotal = Math.Floor(DistanciaTotal / 1000);
				return  distanciaTotal.ToString() + " Km ";
			}
		}
    }
}
