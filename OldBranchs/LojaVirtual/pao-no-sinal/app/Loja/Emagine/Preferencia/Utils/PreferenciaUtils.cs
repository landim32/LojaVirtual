using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Radar.Factory;
using Radar.Model;
using Xamarin.Forms;
using Emagine.Model;

namespace Radar.BLL
{
    public static class PreferenciaUtils
    {
        private const string URL_ATUALIZACAO = "http://pavmanager.com.br/maparadar.txt";

        private static PreferenciaBLL _regraPreferencia;

        private const int ZOOM_MAPA_PADRAO_ANDROID = 18;
        private const float LATITUDE_INICIAL = -15.47f;
        private const float LONGITUDE_INICIAL = -47.55f;

        private const string ALTURA_VOLUME = "alturaVolume";
        private const string CANAL_AUDIO = "canalAudio";
        private const string AO_DESATIVAR_GPS = "aoDesativarGPS";
        private const string DISTANCIA_ALERTA_URBANO = "distanciaAlertaUrbano";
        private const string DISTANCIA_ALERTA_ESTRADA = "distanciaAlertaEstrada";
        private const string INTERVALO_VERIFICACAO = "intervaloVerificacao";
        private const string NIVEL_ZOOM = "nivelZoom";
        private const string SOM_ALARME = "somAlarme";
        private const string TEMPO_ALERTA = "tempoAlerta";
        private const string TEMPO_DURACAO = "tempoDuracao";
        private const string TEMPO_PERCURSO = "tempoPercurso";
        private const string PEDAGIO = "pedagio";
        private const string POLICIA_RODOVIARIA = "policiaRodoviaria";
        private const string RADAR_MOVEL = "radarMovel";
        private const string ROTACIONAR_MAPA = "rotacionarMapa";
        private const string SALVAR_PERCURSO = "salvarPercurso";
        private const string SINAL_GPS = "sinalGPS";
        private const string SOBREPOSICAO_VISUAL = "sobreposicaoVisual";
        private const string SOM_CAIXA = "somCaixa";
        private const string SUAVIZAR_ANIMACAO = "suavizarAnimacao";
        private const string ALERTA_INTELIGENTE = "alertaInteligente";
        private const string ALERTA_SONORO = "alertaSonoro";
        private const string BEEP_AVISO = "beepAviso";
        private const string BUSSOLA = "bussola";
        private const string VOZ_HABILITADA = "vozHabilitada";
        private const string ENCURTAR = "encurtar";
        private const string EXCLUIR_ANTIGO = "excluirAntigos";
        private const string EXIBIR_BOTAO_ADICIONAR = "exibirBotaoAdicionar";
        private const string EXIBIR_BOTAO_REMOVER = "exibirBotaoRemover";
        private const string IMAGEM_SATELITE = "imagemSatelite";
        private const string INICIO_DESLIGAMENTO = "inicioDesligamento";
        private const string INFO_TRAFEGO = "infoTrafego";
        public const string LIGAR_DESLIGAR = "ligarDesligar";
        private const string LOMBADA = "lombada";
        private const string VERIFICAR_INICIAR = "verificarIniciar";
        private const string VIBRAR_ALERTA = "vibrarAlerta";
        private const string VOLUME_PERSONALIZADO = "volumePersonalizado";
        private const string ULTIMA_VERIFICACAO = "ultima_verificacao";
        private const string ULTIMA_ATUALIZACAO = "ultima_atualizacao";

        public static void inicializar() {
            if (_regraPreferencia == null)
                _regraPreferencia = PreferenciaFactory.create();
        }

        public static bool Gratis {
            get {
                return true;
            }
        }

        public static bool AlertaInteligente
		{
			get {
                inicializar();
				return _regraPreferencia.pegarBool(ALERTA_INTELIGENTE);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(ALERTA_INTELIGENTE, value);
            }
		}

        public static bool AlertaSonoro
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(ALERTA_SONORO);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(ALERTA_SONORO, value);
            }
        }

        public static int AlturaVolume
        {
            get {
                inicializar();
                return _regraPreferencia.pegarInt(ALTURA_VOLUME, 15);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(ALTURA_VOLUME, value);
            }
        }

        public static int AnguloRadar
		{
			get
			{
				return 30;
			}
		}

		public static int AnguloCone
		{
			get
			{
				return 45;
			}
		}

		public static bool BeepAviso
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(BEEP_AVISO, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(BEEP_AVISO, value);
            }
		}

        /// <summary>
        /// Exibir a Bussola no Mapa
        /// </summary>
		public static bool Bussola
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(BUSSOLA, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(BUSSOLA, value);
            }
		}

        public static AudioCanalEnum CanalAudio
        {
            get {
                inicializar();
                //return (AudioCanalEnum) _regraPreferencia.pegarInt(CANAL_AUDIO, (int)AudioCanalEnum.Musica);
                return (AudioCanalEnum)_regraPreferencia.pegarInt(CANAL_AUDIO, (int)AudioCanalEnum.Alarme);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(CANAL_AUDIO, (int)value);
            }
        }

        public static bool HabilitarVoz
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(VOZ_HABILITADA, false);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(VOZ_HABILITADA, value);
            }
        }

        public static AoDesativarGPSEnum AoDesativarGPS
        {
            get {
                inicializar();
                return (AoDesativarGPSEnum)_regraPreferencia.pegarInt(AO_DESATIVAR_GPS, (int) AoDesativarGPSEnum.FecharOPrograma);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(AO_DESATIVAR_GPS, (int)value);
            }

        }

        public static int DistanciaAlertaUrbano
        {
            get
            {
                inicializar();
                return _regraPreferencia.pegarInt(DISTANCIA_ALERTA_URBANO, 250);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(DISTANCIA_ALERTA_URBANO, value);
            }
        }

        public static int DistanciaAlertaEstrada
        {
            get
            {
                inicializar();
                return _regraPreferencia.pegarInt(DISTANCIA_ALERTA_ESTRADA, 900);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(DISTANCIA_ALERTA_ESTRADA, value);
            }
        }

        public static bool Encurtar
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(ENCURTAR);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(ENCURTAR, value);
            }
        }

        public static bool ExcluirAntigo
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(EXCLUIR_ANTIGO);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(EXCLUIR_ANTIGO, value);
            }
		}

		public static bool ExibirBotaoAdicionar
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(EXIBIR_BOTAO_ADICIONAR);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(EXIBIR_BOTAO_ADICIONAR, value);
            }
		}

		public static bool ExibirBotaoRemover
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(EXIBIR_BOTAO_REMOVER);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(EXIBIR_BOTAO_REMOVER, value);
            }
		}

        public static int GPSTempoAtualiazacao {
            get {
                return 1000;
            }
        }

        public static int GPSDistanciaAtualizacao {
            get {
                return 0;
            }
        }

        public static double GPSDeltaMax
        {
            get
            {
                return 0.014;
            }
        }

        public static double GPSDeltaPadrao
        {
            get
            {
                return 0.008;
            }
        }

        public static bool ImagemSatelite
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(IMAGEM_SATELITE);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(IMAGEM_SATELITE, value);
            }
		}

        public static bool InicioDesligamento
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(INICIO_DESLIGAMENTO);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(INICIO_DESLIGAMENTO, value);
            }
        }

        public static bool InfoTrafego
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(INFO_TRAFEGO);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(INFO_TRAFEGO, value);
            }
		}

		public static int IntervaloVerificacao
		{
			get {
                inicializar();
                return _regraPreferencia.pegarInt(INTERVALO_VERIFICACAO);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(INTERVALO_VERIFICACAO, value);
            }
		}

        public static bool LigarDesligar
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(LIGAR_DESLIGAR);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(LIGAR_DESLIGAR, value);
            }
        }

        public static bool Lombada
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(LOMBADA, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(LOMBADA, value);
            }
		}

        public static float MapaTilt
        {
            get
            {
                return 65;
            }
        }

		public static int NivelZoom
		{
			get
			{
                inicializar();
                int padrao = 10;
                if (Device.OS == TargetPlatform.Android)
                    padrao = ZOOM_MAPA_PADRAO_ANDROID;
                return _regraPreferencia.pegarInt(NIVEL_ZOOM, padrao);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(NIVEL_ZOOM, value);
            }
		}

		public static bool Pedagio
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(PEDAGIO, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(PEDAGIO, value);
            }
		}

		public static bool PoliciaRodoviaria
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(POLICIA_RODOVIARIA, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(POLICIA_RODOVIARIA, value);
            }
		}

		public static bool RadarMovel
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(RADAR_MOVEL, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(RADAR_MOVEL, value);
            }
		}

		public static bool RotacionarMapa
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(ROTACIONAR_MAPA);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(ROTACIONAR_MAPA, value);
            }
		}

		public static bool SalvarPercurso
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(SALVAR_PERCURSO, false);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(SALVAR_PERCURSO, value);
            }
		}

        /// <summary>
        /// Exibir Sinal de GPS no Mapa
        /// </summary>
		public static bool SinalGPS
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(SINAL_GPS, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(SINAL_GPS, value);
            }
		}

        public static bool SobreposicaoVisual
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(SOBREPOSICAO_VISUAL);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(SOBREPOSICAO_VISUAL, value);
            }
        }

        public static SomAlarmeEnum SomAlarme
        {
            get {
                inicializar();
                return (SomAlarmeEnum) _regraPreferencia.pegarInt(SOM_ALARME, (int) SomAlarmeEnum.Alarme01);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(SOM_ALARME, (int)value);
            }
        }

        public static bool CaixaSom
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(SOM_CAIXA);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(SOM_CAIXA, value);
            }
		}  

        public static bool SuavizarAnimacao
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(SUAVIZAR_ANIMACAO, true);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(SUAVIZAR_ANIMACAO, value);
            }
		}

        public static int TempoAlerta
        {
            get {
                inicializar();
                return _regraPreferencia.pegarInt(TEMPO_ALERTA);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(TEMPO_ALERTA, value);
            }
        }

        public static int TempoDuracaoVibracao
        {
            get {
                inicializar();
                return _regraPreferencia.pegarInt(TEMPO_DURACAO, 1);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(TEMPO_DURACAO, value);
            }
        }

        public static int TempoPercurso
        {
            get {
                inicializar();
                return _regraPreferencia.pegarInt(TEMPO_PERCURSO);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(TEMPO_PERCURSO, value);
            }
        }

        public static bool VerificarIniciar
        {
            get {
                inicializar();
                return _regraPreferencia.pegarBool(VERIFICAR_INICIAR);
            }
            set {
                inicializar();
                _regraPreferencia.gravar(VERIFICAR_INICIAR, value);
            }
        }

        public static bool VibrarAlerta
		{
			get {
                inicializar();
                return _regraPreferencia.pegarBool(VIBRAR_ALERTA);
			}
            set {
                inicializar();
                _regraPreferencia.gravar(VIBRAR_ALERTA, value);
            }
		}

        public static DateTime UltimaVerificacao
        {
            get
            {
                inicializar();
                long ticks = _regraPreferencia.pegarLong(ULTIMA_VERIFICACAO);
                return new DateTime(ticks);
            }
            set
            {
                inicializar();
                _regraPreferencia.gravar(ULTIMA_VERIFICACAO, value.Ticks);
            }
        }

        public static DateTime UltimaAtualizacao
        {
            get
            {
                inicializar();
                long ticks = _regraPreferencia.pegarLong(ULTIMA_ATUALIZACAO, 0);
                return (ticks == 0) ? DateTime.MinValue : new DateTime(ticks);
            }
            set
            {
                inicializar();
                _regraPreferencia.gravar(ULTIMA_ATUALIZACAO, value.Ticks);
            }
        }

        public static string UrlAtualizacao {
            get {
                return URL_ATUALIZACAO;
            }
        }

        public static float LatitudeInicial {
            get {
                return LATITUDE_INICIAL;
            }
        }

        public static float LongitudeInicial {
            get {
                return LONGITUDE_INICIAL;
            }
        }
    }
}
