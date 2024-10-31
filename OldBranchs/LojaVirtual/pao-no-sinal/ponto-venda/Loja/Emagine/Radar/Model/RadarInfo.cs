using SQLite;
using System;


namespace Radar.Model
{
    [Table("radar")]
    public class RadarInfo
    {
        private int _Id;
        private double _Latitude;
        private double _Longitude;
        private double _LatitudeCos;
        private double _LongitudeCos;
        private double _LatitudeSin;
        private double _LongitudeSin;
        private RadarTipoEnum _Tipo;
        private int _Velocidade;
        private int _Direcao;
		private string _Endereco;
		private bool _Ativo = true;
        [PrimaryKey, AutoIncrement, Obsolete("Usando em Id")]
        public int id_radar {
            get {
                return _Id;
            }
            set {
                _Id = value;
            }
        }

		public bool Ativo {
			get {
				return _Ativo;
			}
			set {
				_Ativo = value;
			}
		}
		
		public DateTime UltimaAlteracao { get; set; }

        [Ignore]
		public string Titulo
		{
			get {
                string titulo = "";
                if (UltimaAlteracao == DateTime.MinValue) {
                    switch (_Tipo) {
                        case RadarTipoEnum.Lombada:
                            titulo = "Lombada";
                            break;
                        case RadarTipoEnum.Pedagio:
                            titulo = "Pedágio";
                            break;
                        case RadarTipoEnum.PoliciaRodoviaria:
                            titulo = "Polícia Rodoviária";
                            break;
                        case RadarTipoEnum.RadarFixo:
                            titulo = "Radar Fixo";
                            break;
                        case RadarTipoEnum.RadarMovel:
                            titulo = "Radar Móvel";
                            break;
                        case RadarTipoEnum.SemaforoComCamera:
                            titulo = "Semáforo com Câmera";
                            break;
                        case RadarTipoEnum.SemaforoComRadar:
                            titulo = "Semáforo com Radar";
                            break;
                        default:
                            titulo = "Indefinido";
                            break;
                    }
                }
                else {
                    titulo = UltimaAlteracao.ToString("dd/MMM - HH:mm");
                }
                return titulo;
			}
		}

        [Obsolete("Usando Latitude")]
        public double lat {
            get {
                return _Latitude;
            }
            set {
                _Latitude = value;
            }
        }

        [Obsolete("Usando Longitude")]
        public double lon {
            get {
                return _Longitude;
            }
            set {
                _Longitude = value;
            }
        }

        [Obsolete("Usando LatitudeCos")]
        public double latcos {
            get {
                return _LatitudeCos;
            }
            set {
                _LatitudeCos = value;
            }
        }

        [Obsolete("Usando LatitudeSin")]
        public double latsin {
            get {
                return _LatitudeSin;
            }
            set {
                _LatitudeSin = value;
            }
        }

        [Obsolete("Usando LongitudeCos")]
        public double loncos {
            get {
                return _LongitudeCos;
            }
            set {
                _LongitudeCos = value;
            }
        }

        [Obsolete("Usando LongitudeSin")]
        public double lonsin {
            get {
                return _LongitudeSin;
            }
            set {
                _LongitudeSin = value;
            }
        }

        [Obsolete("Usando Tipo")]
        public int type {
            get {
                return (int)_Tipo;
            }
            set {
                _Tipo = (RadarTipoEnum)value;
            }
        }

        [Obsolete("Usando Velocidade")]
        public int speed {
            get {
                return _Velocidade;
                //return 40;
            }
            set {
                _Velocidade = value;
            }
        }

        public int dirtype { get; set; }

        [Obsolete("Usando a Direcao.")]
        public int direction {
            get {
                return _Direcao;
            }
            set {
                _Direcao = value;
            }
        }

        [Obsolete("Não está usando em nenhum lugar.")]
        public int usuario { get; set; }

        [Ignore]
        public int Id {
            get {
                return _Id;
            }
            set {
                _Id = value;
            }
        }

        [Ignore]
        public double Latitude {
            get {
                return _Latitude;
            }
            set {
                _Latitude = value;
            }
        }

		[Ignore]
		public string LatitudeText
		{
			get
			{
				return "Latitude: " + _Latitude.ToString() + " ";
			}

		}

        [Ignore]
        public double Longitude {
            get {
                return _Longitude;
            }
            set {
                _Longitude = value;
            }
        }

		[Ignore]
		public String LongitudeText
		{
			get
			{
				return "Longitude: " +_Longitude.ToString() + " ";
			}
		}

        [Ignore]
        public double LatitudeCos {
            get {
                return _LatitudeCos;
            }
            set {
                _LatitudeCos = value;
            }
        }

        [Ignore]
        public double LongitudeCos {
            get {
                return _LongitudeCos;
            }
            set {
                _LongitudeCos = value;
            }
        }

        [Ignore]
        public double LatitudeSin {
            get {
                return _LatitudeSin;
            }
            set {
                _LatitudeSin = value;
            }
        }

        [Ignore]
        public double LongitudeSin {
            get {
                return _LongitudeSin;
            }
            set {
                _LongitudeSin = value;
            }
        }

        [Ignore]
        public RadarTipoEnum Tipo {
            get {
                return _Tipo;
            }
            set {
                _Tipo = value;
            }
        }

        [Ignore]
        public int Velocidade {
            get {
                return _Velocidade;
                //return 40;
            }
            set {
                _Velocidade = value;
            }
        }

        [Ignore]
        public string VelocidadeStr
        {
            get {
                return "Limite: " + Velocidade.ToString() + "km/h ";
            }
        }

        [Ignore]
        public int Direcao {
            get {
                return _Direcao;
            }
            set {
                _Direcao = value;
            }
        }

		[Ignore]
		public string DirecaoText
		{
			get
			{
				return "Ângulo: " + _Direcao.ToString() + " ";
			}

		}

		public string Endereco
		{
			get
			{
				return _Endereco;
			}
			set
			{
				_Endereco = value;
			}
		}

        [Ignore]
        public bool Usuario {
            get {
                return (usuario == 1);
            }
            set {
                usuario = (value) ? 1 : 0;
            }
        }

        [Ignore]
        public string Imagem {
            get {
                string str = "meusradares.png";
                switch (_Tipo) {
                    case RadarTipoEnum.Lombada:
                        str = "lombada.png";
                        break;
                    case RadarTipoEnum.Pedagio:
                        str = "pedagio.png";
                        break;
                    case RadarTipoEnum.PoliciaRodoviaria:
                        str = "policiarodoviaria.png";
                        break;
                    case RadarTipoEnum.RadarFixo:
                        if (Usuario)
                        {
                            str = "cameramais.png";
                        }
                        else {
                            if (Velocidade >= 20 && Velocidade < 30)
                                str = "radar_20.png";
                            else if (Velocidade >= 30 && Velocidade < 40)
                                str = "radar_30.png";
                            else if (Velocidade >= 40 && Velocidade < 50)
                                str = "radar_40.png";
                            else if (Velocidade >= 50 && Velocidade < 60)
                                str = "radar_50.png";
                            else if (Velocidade >= 60 && Velocidade < 70)
                                str = "radar_60.png";
                            else if (Velocidade >= 70 && Velocidade < 80)
                                str = "radar_70.png";
                            else if (Velocidade >= 80 && Velocidade < 90)
                                str = "radar_80.png";
                            else if (Velocidade >= 90 && Velocidade < 100)
                                str = "radar_90.png";
                            else if (Velocidade >= 100 && Velocidade < 110)
                                str = "radar_100.png";
                            else if (Velocidade >= 110 && Velocidade < 120)
                                str = "radar_110.png";
                            else
                                str = "cameramais.png";
                        }
                        break;
                    case RadarTipoEnum.RadarMovel:
                        str = "radar_movel.png";
                        break;
                    case RadarTipoEnum.SemaforoComCamera:
                        str = "semaforo.png";
                        break;
                    case RadarTipoEnum.SemaforoComRadar:
                        str = "radar_40_semaforo.png";
                        break;
                    default:
                        str = "semaforo.png";
                        break;
                }
                return str;
            }
        }
    }

}
