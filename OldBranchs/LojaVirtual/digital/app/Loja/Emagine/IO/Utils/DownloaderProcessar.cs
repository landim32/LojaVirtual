using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Utils
{
    public class DownloaderProcessar: DownloaderArquivo
    {
        public DownloaderProcessar() {
            _fecharDownload = false;
        }

        public delegate void AoProcessarArquivoHandler(object sender, ArquivoProcessarArgs e);
        public event AoProcessarArquivoHandler AoProcessarArquivo;

        protected void processarArquivo(int processoAtual, int maxProcesso) {
            if (_janela != null) {
                double valor = ((double)processoAtual / (double)maxProcesso) * 100;
                _janela.Progresso = (int) Math.Floor(valor);
                _janela.Descricao = processoAtual.ToString() + "/" + maxProcesso.ToString();
            }
            if (AoProcessarArquivo != null)
                AoProcessarArquivo(this, new ArquivoProcessarArgs(processoAtual, maxProcesso));
        }
    }

    public class ArquivoProcessarArgs : EventArgs {

        public ArquivoProcessarArgs(int processoAtual, int maxProcesso) {
            ProcessoAtual = processoAtual;
            MaxProcesso = maxProcesso;
        }

        public int ProcessoAtual { get; set; }
        public int MaxProcesso { get; set; }
    }
}
