using Emagine.IBLL;
using Emagine.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Utils
{
    public static class AudioUtils
    {
        private const float VOLUME_MAXIMO = 15;
        private static IAudio _audio;

        private static void inicializarAudio() {
            if (_audio == null)
            {
                _audio = DependencyService.Get<IAudio>();
                _audio.Volume = VOLUME_MAXIMO;
                _audio.CaixaSom = false;
                _audio.Canal = AudioCanalEnum.Notificacao;
            }
        }

        public static float Volume {
            get {
                inicializarAudio();
                return _audio.Volume;
            }
            set {
                inicializarAudio();
                _audio.Volume = value;
            }
        }

        public static bool CaixaSom
        {
            get {
                inicializarAudio();
                return _audio.CaixaSom;
            }
            set {
                inicializarAudio();
                _audio.CaixaSom = value;
            }
        }

        public static AudioCanalEnum Canal {
            get {
                inicializarAudio();
                return _audio.Canal;
            }
            set {
                inicializarAudio();
                _audio.Canal = value;
            }
        }

        public static void play(string arquivo)
        {
            inicializarAudio();
            _audio.play(arquivo);
        }

        public static void play(string[] arquivos)
        {
            inicializarAudio();
            _audio.play(arquivos);
        }

        public static void stop() {
            inicializarAudio();
            _audio.stop();
        }
    }
}
