using Emagine.Model;
using Emagine.Utils;
using Radar.Model;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Radar.BLL
{
    public class AvisoSonoroBLL
    {
        private const string DIR_AUDIO = "audios";
        private const string DIR_ALARME = "alarmes";

        public string pegarArquivo(SomAlarmeEnum audio)
        {
            string arquivo;
            switch (audio)
            {
                case SomAlarmeEnum.Alarme02:
                    arquivo = "alarm_002";
                    break;
                case SomAlarmeEnum.Alarme03:
                    arquivo = "alarm_003";
                    break;
                case SomAlarmeEnum.Alarme04:
                    arquivo = "alarm_004";
                    break;
                case SomAlarmeEnum.Alarme05:
                    arquivo = "alarm_005";
                    break;
                case SomAlarmeEnum.Alarme06:
                    arquivo = "alarm_006";
                    break;
                case SomAlarmeEnum.Alarme07:
                    arquivo = "alarm_007";
                    break;
                case SomAlarmeEnum.Alarme08:
                    arquivo = "alarm_008";
                    break;
                case SomAlarmeEnum.Alarme09:
                    arquivo = "alarm_009";
                    break;
                case SomAlarmeEnum.Alarme10:
                    arquivo = "alarm_010";
                    break;
                case SomAlarmeEnum.Alarme11:
                    arquivo = "alarm_011";
                    break;
                case SomAlarmeEnum.Alarme12:
                    arquivo = "alarm_012";
                    break;
                case SomAlarmeEnum.Alarme13:
                    arquivo = "alarm_013";
                    break;
                default:
                    arquivo = "alarm_001";
                    break;
            }
            return arquivo;
        }

        private IDictionary<RadarTipoEnum, string> AUDIO_RADAR = new Dictionary<RadarTipoEnum, string>() {
            {  RadarTipoEnum.Lombada, "lombada.mp3" },
            {  RadarTipoEnum.Pedagio, "pedagio.mp3" },
            {  RadarTipoEnum.PoliciaRodoviaria, "policia-rodoviaria.mp3" },
            {  RadarTipoEnum.RadarFixo, "radar-fixo.mp3" },
            {  RadarTipoEnum.RadarMovel, "radar-movel.mp3" },
            {  RadarTipoEnum.SemaforoComRadar, "radar-semaforo.mp3" }
        };

        private IDictionary<int, string> AUDIO_DISTANCIA = new Dictionary<int, string>() {
            { 100, "metros-100.mp3" },
            { 200, "metros-200.mp3" },
            { 300, "metros-300.mp3" },
            { 400, "metros-400.mp3" },
            { 500, "metros-500.mp3" },
            { 600, "metros-600.mp3" },
            { 700, "metros-700.mp3" },
            { 800, "metros-800.mp3" },
            { 900, "metros-900.mp3" },
            { 1000, "metros-1000.mp3" }
        };

        private IDictionary<int, string> AUDIO_VELOCIDADE = new Dictionary<int, string>() {
            { 10, "limite-10-km.mp3" },
            { 20, "limite-20-km.mp3" },
            { 30, "limite-30-km.mp3" },
            { 40, "limite-40-km.mp3" },
            { 50, "limite-50-km.mp3" },
            { 60, "limite-60-km.mp3" },
            { 70, "limite-70-km.mp3" },
            { 80, "limite-80-km.mp3" },
            { 90, "limite-90-km.mp3" },
            { 100, "limite-100-km.mp3" },
            { 110, "limite-110-km.mp3" },
            { 120, "limite-120-km.mp3" }
        };

        public void play(RadarTipoEnum tipoRadar, int distancia)
        {
            play(tipoRadar, 0, distancia);
        }

        public void play(SomAlarmeEnum alarme)
        {
            var arquivoStr = Path.Combine(DIR_ALARME, pegarArquivo(alarme)) + ".mp3";
            if (Device.OS == TargetPlatform.iOS)
            {
                AudioUtils.Volume = PreferenciaUtils.AlturaVolume;
                AudioUtils.Canal = PreferenciaUtils.CanalAudio;
                AudioUtils.CaixaSom = PreferenciaUtils.CaixaSom;
                AudioUtils.play(arquivoStr);
            }
            else {
                if (PreferenciaUtils.CanalAudio != AudioCanalEnum.Notificacao)
                {
                    AudioUtils.Volume = PreferenciaUtils.AlturaVolume;
                    AudioUtils.Canal = PreferenciaUtils.CanalAudio;
                    AudioUtils.CaixaSom = PreferenciaUtils.CaixaSom;
                    AudioUtils.play(arquivoStr);
                }
            }
        }

        public void play(RadarTipoEnum tipoRadar, int velocidade, int distancia) {
            IList<string> audios = new List<string>();
            if (PreferenciaUtils.BeepAviso) {
                audios.Add(Path.Combine(DIR_ALARME, pegarArquivo(PreferenciaUtils.SomAlarme) + ".mp3"));
            }
            audios.Add(Path.Combine(DIR_AUDIO, AUDIO_RADAR[tipoRadar]));
            if (velocidade > 0)
                audios.Add(Path.Combine(DIR_AUDIO, AUDIO_VELOCIDADE[velocidade]));
            if (distancia > 0)
                audios.Add(Path.Combine(DIR_AUDIO, AUDIO_DISTANCIA[distancia]));
            AudioUtils.Volume = PreferenciaUtils.AlturaVolume;
            AudioUtils.Canal = PreferenciaUtils.CanalAudio;
            AudioUtils.CaixaSom = PreferenciaUtils.CaixaSom;
            AudioUtils.play(audios.ToArray());
        }
    }
}
