using Emagine.IBLL;
using Emagine.Popup;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Utils
{
    public class DownloaderArquivo
    {
        private IDownloader _cliente;
        protected ProgressBarPopUp _janela;
        protected bool _fecharDownload = true;

        public delegate void AoCompletarHandler(object sender, string nomeArquivo);

        public event AoCompletarHandler aoCompletar;

        public DownloaderArquivo() {
            _cliente = DependencyService.Get<IDownloader>();
            _cliente.aoProcessar += (object sender, AoProcessarArgs e) => {
                if (_janela != null) {
                    _janela.Progresso = e.Porcentagem;
                    _janela.Descricao = e.TamanhoBaixado.ToString() + "/" + e.TamanhoArquivo.ToString();
                }                
            };
            _cliente.aoCompletar += (sender, e) => {
                if (aoCompletar != null)
                    aoCompletar(sender, _cliente.NomeArquivo);
                if (_fecharDownload)
                    fecharPopup();
            };
            _cliente.aoCancelar += (sender, e) => {
                fecharPopup();
            };
        }

        protected virtual void abrirPopup() {
            _janela = new ProgressBarPopUp();
            Application.Current.MainPage.Navigation.PushModalAsync(_janela);
        }

        protected void reiniciarBarraProgresso() {
            if (_janela != null)
                _janela.Progresso = 0;
        }

        protected virtual void fecharPopup()
        {
            if (_janela != null)
            {
                Application.Current.MainPage.Navigation.PopModalAsync();
                _janela = null;
            }
        }

        public void download(string url, string nomeArquivo) {
            abrirPopup();
            //_janela.Navigation.PushModalAsync(_janela);
            _cliente.download(url, nomeArquivo);
        }
    }
}
