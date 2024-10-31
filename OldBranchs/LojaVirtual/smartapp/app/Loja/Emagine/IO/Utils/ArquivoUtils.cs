using Emagine.IBLL;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Utils
{
    public static class ArquivoUtils
    {
        private static IArquivo _arquivo;

        public static bool existe(string nomeArquivo) {
            if (_arquivo == null)
                _arquivo = DependencyService.Get<IArquivo>();
            return _arquivo.existe(nomeArquivo);
        }

        public static string abrirTexto(string nomeArquivo) {
            if (_arquivo == null)
                _arquivo = DependencyService.Get<IArquivo>();
            return _arquivo.abrirTexto(nomeArquivo);
        }

        public static void salvarTexto(string nomeArquivo) {
            if (_arquivo == null)
                _arquivo = DependencyService.Get<IArquivo>();
            _arquivo.salvarTexto(nomeArquivo);
        }

        public static byte[] abrir(string nomeArquivo) {
            if (_arquivo == null)
                _arquivo = DependencyService.Get<IArquivo>();
            return _arquivo.abrir(nomeArquivo);
        }

        public static void salvar(string nomeArquivo, byte[] buffer) {
            if (_arquivo == null)
                _arquivo = DependencyService.Get<IArquivo>();
            _arquivo.salvar(nomeArquivo, buffer);
        }
    }
}
