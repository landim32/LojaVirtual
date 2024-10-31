using Radar.DALFactory;
using Radar.DALSQLite;
using Radar.IDAL;
using Radar.Model;
using Radar.Utils;
using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Emagine.Utils;
using Xamarin.Forms.Maps;

namespace Radar.BLL
{
    public class RadarBLL
    {
        //private const int TIPO_RADAR_NORMAL = 1;

        private IRadarDAL _db;
        private IDictionary<string, bool> _radares = new Dictionary<string, bool>();

        private static RadarInfo _radarAtual;

        public static RadarInfo RadarAtual
        {
            get
            {
                return _radarAtual;
            }
            set {
                _radarAtual = value;
            }
        }

        public RadarBLL() {
            _db = RadarDALFactory.create();
        }

        public IList<RadarInfo> listar()
        {
            return _db.listar();
        }

        public IList<RadarInfo> listarUsuario()
        {
            return _db.listarUsuario();
        }

        public IList<RadarInfo> listarInativo()
        {
            return _db.listarInativo();
        }

        public IList<RadarInfo> listar(RadarBuscaInfo busca) {
            return _db.listar(busca);
        }

        public IList<RadarInfo> listar(double latitude, double longitude, double latitudeDelta, double longitudeDelta)
        {
            var filtros = listarRadarTipo();
            return _db.listar(latitude, longitude, latitudeDelta, longitudeDelta, filtros);
        }

        public RadarInfo pegar(int idRadar)
        {
            return _db.pegar(idRadar);
        }

        public int gravar(RadarInfo radar)
        {
			//if (radar.Velocidade < 20)
			//    throw new Exception("Você não pode adicionar um radar a menos de 20 km/h.");
            radar.UltimaAlteracao = DateTime.Now;
			int alteracao = _db.gravar(radar);
            atualizarEndereco();
            return alteracao;
        }

        public int inserir(LocalizacaoInfo local) {
			
			//DateTime saveNow = DateTime.Now;
            int velocidade = (int)Math.Floor(local.Velocidade);
            velocidade = ((velocidade % 10) > 0) ? (velocidade - (velocidade % 10)) + 10 : velocidade;
            RadarInfo radar = new RadarInfo {
                Id = 0,
                Latitude = local.Latitude,
                Longitude = local.Longitude,
                LatitudeCos = Math.Cos(local.Latitude * Math.PI / 180),
                LatitudeSin = Math.Sin(local.Latitude * Math.PI / 180),
                LongitudeCos = Math.Cos(local.Longitude * Math.PI / 180),
                LongitudeSin = Math.Sin(local.Longitude * Math.PI / 180),
                Direcao = (int) Math.Floor(local.Sentido),
                Velocidade = velocidade,
                Tipo = RadarTipoEnum.RadarFixo,
				UltimaAlteracao = DateTime.Now,
				Endereco = "",
                Usuario = true
            };
			return _db.gravar(radar);
        }

        public void excluir(RadarInfo radar)
        {
            if (radar != null)
            {
                radar.Ativo = false;
                radar.UltimaAlteracao = DateTime.Now;
                _db.gravar(radar);
            }
        }

        private double angleFromCoordinate(double latitude1, double longitude1, double latitude2, double longitude2) {
            double dLong = longitude2 - longitude1;
            double y = Math.Sin(dLong) * Math.Cos(latitude2);
            double x = Math.Cos(latitude1) * Math.Sin(latitude2) - Math.Sin(latitude1) * Math.Cos(latitude2) * Math.Cos(dLong);

            double brng = Math.Atan2(y, x);
            brng = brng * (180 / Math.PI);
            brng = (brng + 360) % 360;
            brng = 360 - brng;
            return brng;
        }

        private void limparAlertado(double Latitude, double Longitude, double distanciaRadar) {
            /*
            int radarAtivo = 0;
            int radarTodo = 0;
            foreach (KeyValuePair<string, bool> alerta in _radares) {
                radarTodo++;
                if (alerta.Value)
                    radarAtivo++;
            }
            */
            List<string> desativado = new List<string>();
            foreach (KeyValuePair<string, bool> alerta in _radares) {
                string str = alerta.Key.Substring(0, alerta.Key.IndexOf('|'));
                double latitudeRadar = Convert.ToDouble(str);
                str = alerta.Key.Substring(alerta.Key.IndexOf('|') + 1);
                double longitudeRadar = Convert.ToDouble(str);
                double distancia = Math.Floor(GPSUtils.calcularDistancia(Latitude, Longitude, latitudeRadar, longitudeRadar));
                if (distancia > distanciaRadar && _radares.ContainsKey(alerta.Key))
                    //_radares[alerta.Key] = false;
                    desativado.Add(alerta.Key);
            }
            foreach (string posicao in desativado)
                _radares[posicao] = false;
        }

        /*
        private void alertar(LocalizacaoInfo local, RadarInfo radar) {
            string mensagem = "Tem um radar a frente, diminua para " + radar.Velocidade.ToString() + "km/h!";
            MensagemUtils.notificar(RADAR_ID, "Alerta de Radar", mensagem);
        }
        */

        public bool radarEstaAFrente(LocalizacaoInfo local, RadarInfo radar) {
            //if (local.Velocidade > radar.Velocidade) {
            double anguloRelacaoRadar = local.Sentido - radar.Direcao;
            if (((Math.Abs(anguloRelacaoRadar) % 360) <= PreferenciaUtils.AnguloRadar) || ((360 - Math.Abs(anguloRelacaoRadar)) % 360) <= PreferenciaUtils.AnguloRadar)
            {
                string posLatLong = radar.Latitude.ToString() + "|" + radar.Longitude.ToString();
                if ((_radares.ContainsKey(posLatLong) && _radares[posLatLong] == false) || !_radares.ContainsKey(posLatLong))
                {

                    double anguloRadar = angleFromCoordinate(local.Latitude, local.Longitude, radar.Latitude, radar.Longitude);
                    //double meuAngulo = local.Sentido;

                    double anguloDiferencial = local.Sentido - anguloRadar;
                    if (anguloDiferencial < 0)
                        anguloDiferencial += 360;
                    if (anguloDiferencial > 360)
                        anguloDiferencial -= 360;

                    if (((Math.Abs(anguloDiferencial) % 360) <= PreferenciaUtils.AnguloCone) || ((360 - Math.Abs(anguloDiferencial)) % 360) <= PreferenciaUtils.AnguloCone)
                    {
                        if (!_radares.ContainsKey(posLatLong))
                            _radares.Add(posLatLong, true);
                        //alertar(local, radar);
                        return true;
                    }
                    else
                        Debug.WriteLine("Radar não está no cone. Meu angulo (" + Math.Floor(local.Sentido) + ") + eu/radar(" + Math.Floor(anguloRadar) + ").");
                }
                else
                    Debug.WriteLine("Radar encontrado mas já foi alertado.");
            }
            else
                Debug.WriteLine("Radar encontrado mas angulo não bate com o do radar = " + Math.Floor(anguloRelacaoRadar) + ".");
            //}
            return false;
        }

        public bool radarContinuaAFrente(LocalizacaoInfo local, RadarInfo radar)
        {
            //if (local.Velocidade > radar.Velocidade) {
            double anguloRelacaoRadar = local.Sentido - radar.Direcao;
            if (((Math.Abs(anguloRelacaoRadar) % 360) <= PreferenciaUtils.AnguloRadar) || ((360 - Math.Abs(anguloRelacaoRadar)) % 360) <= PreferenciaUtils.AnguloRadar)
            {
                double anguloRadar = angleFromCoordinate(local.Latitude, local.Longitude, radar.Latitude, radar.Longitude);

                double anguloDiferencial = local.Sentido - anguloRadar;
                if (anguloDiferencial < 0)
                    anguloDiferencial += 360;
                if (anguloDiferencial > 360)
                    anguloDiferencial -= 360;

                if (((Math.Abs(anguloDiferencial) % 360) <= PreferenciaUtils.AnguloCone) || ((360 - Math.Abs(anguloDiferencial)) % 360) <= PreferenciaUtils.AnguloCone)
                    return true;
                else
                    Debug.WriteLine("Radar não está no cone. Meu angulo (" + Math.Floor(local.Sentido) + ") + eu/radar(" + Math.Floor(anguloRadar) + ").");
            }
            else
                Debug.WriteLine("Radar encontrado mas angulo não bate com o do radar = " + Math.Floor(anguloRelacaoRadar) + ".");
            //}
            return false;
        }

        private IList<RadarTipoEnum> listarRadarTipo() {
            var filtros = new List<RadarTipoEnum>();
            if (PreferenciaUtils.RadarMovel)
                filtros.Add(RadarTipoEnum.RadarMovel);
            if (PreferenciaUtils.Pedagio)
                filtros.Add(RadarTipoEnum.Pedagio);
            if (PreferenciaUtils.PoliciaRodoviaria)
                filtros.Add(RadarTipoEnum.PoliciaRodoviaria);
            if (PreferenciaUtils.Lombada)
                filtros.Add(RadarTipoEnum.Lombada);
            if (filtros.Count() == 4)
            {
                filtros.Clear();
            }   
            else {
                filtros.Add(RadarTipoEnum.RadarFixo);
                filtros.Add(RadarTipoEnum.SemaforoComRadar);
                filtros.Add(RadarTipoEnum.SemaforoComCamera);
            }
            return filtros;
        }


        /// <summary>
        /// Faz todos os calculos referentes a posição do radar referente a posição do usuário
        /// </summary>
        /// <param name="local">Localização enviada pelo GPS.</param>
        public RadarInfo calcularRadar(LocalizacaoInfo local, double distanciaRadar) {

            double latitudeOld = local.Latitude;
            double longitudeOld = local.Longitude;

            /*
            double latitudeCos = Math.Cos(local.Latitude * Math.PI / 180);
            double latitudeSin = Math.Sin(local.Latitude * Math.PI / 180);
            double longitudeCos = Math.Cos(local.Longitude * Math.PI / 180);
            double longitudeSin = Math.Sin(local.Longitude * Math.PI / 180);
            double distanciaCos = Math.Cos((PreferenciaUtils.DistanciaRadar / 1000) / DIAMETRO_TERRA);
            */

            var args = new RadarBuscaInfo {
                latitudeCos = Math.Cos(local.Latitude * Math.PI / 180),
                latitudeSin = Math.Sin(local.Latitude * Math.PI / 180),
                longitudeCos = Math.Cos(local.Longitude * Math.PI / 180),
                longitudeSin = Math.Sin(local.Longitude * Math.PI / 180),
                distanciaCos = Math.Cos((distanciaRadar / 1000) / GPSUtils.DIAMETRO_TERRA),
                Filtros = listarRadarTipo()
            };

            limparAlertado(local.Latitude, local.Longitude, distanciaRadar);

            RadarInfo radarCapturado = null; 
            IList<RadarInfo> radares = _db.listar(args);
            foreach (RadarInfo radar in radares) {
                if (radarEstaAFrente(local, radar)) {
                    radarCapturado = radar;
                    break;
                }
            }
            return radarCapturado;
        }

      
		public async void atualizarEndereco()
		{
			
			if (InternetUtils.estarConectado())
			{
			
				var radares = _db.listarEnderecoNulo();
				if (radares.Count > 0)
				{
                    var radar = radares.FirstOrDefault();
					int idRadar = radares[0].Id;
					float lat = (float)radar.Latitude;
					float lon = (float)radar.Longitude;

					GeocoderUtils.pegarAsync(lat, lon, (sender, e) =>
					{
                        radar.UltimaAlteracao = DateTime.Now;
                        radar.Endereco = e.Endereco.ToString();
						gravar(radar);
						atualizarEndereco();
					});
				}
			}
		}

        public string tipoRadarParaTexto(RadarTipoEnum tipo) {
            string titulo = string.Empty;
            switch (tipo)
            {
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
            return titulo;
        }

		public string imagemRadar(double velocidade)
		{
			string imagem = string.Empty;
			if (velocidade >= 20 && velocidade < 30)
				imagem = "radar_20.png";
			else if (velocidade >= 30 && velocidade < 40)
				imagem = "radar_30.png";
			else if (velocidade >= 40 && velocidade < 50)
				imagem = "radar_40.png";
			else if (velocidade >= 50 && velocidade < 60)
				imagem = "radar_50.png";
			else if (velocidade >= 60 && velocidade < 70)
				imagem = "radar_60.png";
			else if (velocidade >= 70 && velocidade < 80)
				imagem = "radar_70.png";
			else if (velocidade >= 80 && velocidade < 90)
				imagem = "radar_80.png";
			else if (velocidade >= 90 && velocidade < 100)
				imagem = "radar_90.png";
			else if (velocidade >= 100 && velocidade < 110)
				imagem = "radar_100.png";
			else if (velocidade >= 110 && velocidade < 120)
				imagem = "radar_110.png";
			else
				imagem = "cameramais.png";
			return imagem;
		}
    }
}
