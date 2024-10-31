using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.IBLL
{

    public interface IDownloader
    {
        string NomeArquivo { get; }
        void download(string url, string nomeArquivo);
        void cancelar();
        EventHandler<AoProcessarArgs> aoProcessar { get; set; }
        EventHandler aoCompletar { get; set; }
        EventHandler aoCancelar { get; set; }
    }

    public class AoProcessarArgs : EventArgs
    {
        public AoProcessarArgs(int porcentagem, long tamanhoBaixado, long tamanhoArquivo) {
            Porcentagem = porcentagem;
            TamanhoBaixado = tamanhoBaixado;
            TamanhoArquivo = tamanhoArquivo;
        }

        public int Porcentagem { get; set; }
        public long TamanhoBaixado { get; set; }
        public long TamanhoArquivo { get; set; }

    }
}
