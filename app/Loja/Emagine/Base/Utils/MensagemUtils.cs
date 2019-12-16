using Emagine.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Utils
{
    public static class MensagemUtils
    {
        private static IMensagem _mensagem;

        public static void avisar(string mensagem)
        {
            avisar(String.Empty, mensagem);
        }

        public static void avisar(string titulo, string mensagem)
        {
            if (Device.OS == TargetPlatform.iOS)
                Application.Current.MainPage.DisplayAlert(titulo, mensagem, "Fechar");
			
            else {
                if (_mensagem == null)
                    _mensagem = DependencyService.Get<IMensagem>();
                _mensagem.exibirAviso(titulo, mensagem);
            }
        }

        public static bool notificar(int id, string titulo, string mensagem, int icone = 0, string audio = null, double velocidade = 0)
        {
            if (_mensagem == null)
                _mensagem = DependencyService.Get<IMensagem>();
            return _mensagem.notificar(id, titulo, mensagem, icone, audio, velocidade);
        }

        public static bool notificarPermanente(int id, string titulo, string mensagem, int idParar, string textoParar, string acaoParar) {
            if (_mensagem == null)
                _mensagem = DependencyService.Get<IMensagem>();
            return _mensagem.notificarPermanente(id, titulo, mensagem, idParar, textoParar, acaoParar);
        }

        public static bool pararNotificaoPermanente(int id) {
            if (_mensagem == null)
                _mensagem = DependencyService.Get<IMensagem>();
            return _mensagem.pararNotificaoPermanente(id);
        }

        public static bool enviarEmail(string para, string titulo, string mensagem)
        {
            if (_mensagem == null)
                _mensagem = DependencyService.Get<IMensagem>();
            return _mensagem.enviarEmail(para, titulo, mensagem);
        }

        public static void vibrar(int milisegundo)
        {
            if (_mensagem == null)
                _mensagem = DependencyService.Get<IMensagem>();
            _mensagem.vibrar(milisegundo);
        }
    }
}
