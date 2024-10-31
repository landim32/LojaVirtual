using Emagine.Base.Pages;
using Emagine.Login.Factory;
using Emagine.Social.Factory;
using Plugin.LocalNotifications;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pedido.BLL
{
    public class PedidoServicoBLL: BaseServicoBLL
    {
        private bool _verificando = false;
        private DateTime _ultimaExecucao;
        public int DuracaoExecucao { get; set; } = 10000;

        protected override async Task executar() {
            if (_verificando) {
                return;
            }
            _verificando = true;
            try
            {
                var dataAtual = DateTime.Now;
                if (dataAtual.Subtract(_ultimaExecucao).TotalMilliseconds > DuracaoExecucao) {
                    var regraUsuario = UsuarioFactory.create();
                    var usuario = regraUsuario.pegarAtual();
                    if (usuario != null) {
                        var regraMensagem = MensagemFactory.create();
                        var avisos = await regraMensagem.listarAviso(usuario.Id);
                        foreach (var aviso in avisos) {
                            var rootPage = (RootPage)App.Current.MainPage;
                            //CrossLocalNotifications.Current.Show(rootPage.NomeApp, aviso.Assunto);
                            CrossLocalNotifications.Current.Show(rootPage.NomeApp, aviso.Mensagem);
                        }
                    }
                    _ultimaExecucao = dataAtual;
                    System.Diagnostics.Debug.WriteLine("Verificando avisos: " + dataAtual.ToString("HH:mm:ss"));
                }
            }
            finally {
                _verificando = false;
            }
            return;
        }
    }
}
