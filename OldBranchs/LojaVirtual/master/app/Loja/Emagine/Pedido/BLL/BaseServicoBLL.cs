using System;
using System.Collections.Generic;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Pedido.BLL
{
    public abstract class BaseServicoBLL
    {
        private CancellationTokenSource _cancellation;

        public int Duracao { get; set; } = 1000;

        protected abstract Task executar();

        public void inicializar()
        {
            System.Diagnostics.Debug.WriteLine("Banner: Iniciando rotação...");
            _cancellation = new CancellationTokenSource();
            Device.StartTimer(TimeSpan.FromMilliseconds(Duracao), () => {
                if (_cancellation.IsCancellationRequested)
                {
                    return false;
                }
                //var retorno = true;
                Device.BeginInvokeOnMainThread(async () => {
                    await executar();
                });
                return true;
            });
        }

        public void finalizar()
        {
            Interlocked.Exchange(ref _cancellation, new CancellationTokenSource()).Cancel();
            System.Diagnostics.Debug.WriteLine("Banner: Finalizando rotação...");
        }
    }
}
