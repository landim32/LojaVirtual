using Emagine.Utils;
using Radar.BLL;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Radar.Controls
{
    public class DownloaderAtualizacao: DownloaderProcessar
    {
        private const string NOME_ARQUIVO = "maparadar.txt";

        public DownloaderAtualizacao()
        {
            this.aoCompletar += (sender, nomeArquivo) =>
            {
                fecharPopup();
                MensagemUtils.avisar("Banco de dados atualizado com sucesso.");
                PreferenciaUtils.UltimaVerificacao = DateTime.Now;
                var ultimaAtualizacao = PreferenciaUtils.UltimaAtualizacao;
                if (ultimaAtualizacao == DateTime.MinValue)
                    PreferenciaUtils.UltimaAtualizacao = DateTime.Now;
                /*
                if (_janela != null)
                {
                    _janela.Titulo = "Aguarde alguns segundos...";
                    _janela.Progresso = 1;
                    _janela.Descricao = "";
                }

                if (ArquivoUtils.existe(NOME_ARQUIVO))
                {
                    if (_janela != null)
                    {
                        _janela.Titulo = "Processando arquivo...";
                        _janela.Progresso = 0;
                        _janela.Descricao = "";
                    }
                    var task = Task.Factory.StartNew(() =>
                    {
                        string arquivo = ArquivoUtils.abrirTexto(NOME_ARQUIVO);
                        string[] linhas = arquivo.Split("\n".ToCharArray());

                        int i = 0;
                        foreach (string linha in linhas) {
                            bool executou = false;
                            ThreadUtils.RunOnUiThread(() => {
                                processarArquivo(i, linhas.Count());
                                i++;
                                executou = true;
                            });
                            while (true) {
                                if (executou)
                                    break;
                                Task.Delay(1).Wait();
                            }
                        }
                        MensagemUtils.avisar(nomeArquivo);
                        fecharPopup();
                    });
                    //task.Start();
                }
                else
                    MensagemUtils.avisar("Arquivo não encontrado!");
                */

            };
        }

        public void download() {
            download(PreferenciaUtils.UrlAtualizacao, NOME_ARQUIVO);
        }


    }
}
