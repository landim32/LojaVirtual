using System;
using System.Linq;
using System.Threading.Tasks;
using Acr.UserDialogs;
using Xamarin.Forms;
using Emagine;
using Emagine.Frete.Model;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;

namespace Emagine.Frete.BLL
{
    [Obsolete("Use CaronaUtils. Mais pra frete ainda vai ser alterado.")]
    public static class AtualizacaoFrete
    {
        private static Task task;
        private static bool confirm = false;
        private static int delay = 10000;

        public static void start(){
            task = Task.Factory.StartNew(() =>
            {
                Task.Delay(delay).Wait();
                atualizaPedidosMotoristaAsync().Wait();
            });
        }

        public static void setConfirm(bool value){
            confirm = value;
        } 

        private static async Task atualizaPedidosMotoristaAsync(){
            MotoristaRetornoInfo ret = null;
            if(new MotoristaBLL().pegarAtual() != null 
               && new MotoristaBLL().pegarAtual().Situacao == MotoristaSituacaoEnum.Ativo)
            {
                ret = await new MotoristaBLL().listarPedidosAsync();
                if (!confirm && ret != null)
                {
                    if (ret.IdFrete == null && ret.Pedidos != null && ret.Pedidos.Count > 0)
                    {
                        Device.BeginInvokeOnMainThread(async () =>
                        {
                            var pedido = ret.Pedidos.First();
                            confirm = await UserDialogs.Instance.ConfirmAsync("Nova entrega no valor de R$ " + pedido.Valor.ToString("N2") + " disponível para iniciar.", "Entrega", "Ver", "Não quero");
                            if (confirm)
                            {
                                ((RootPage)App.Current.MainPage).PushAsync(new Pages.FreteResumoPage(pedido));
                            }
                            else
                            {
                                //var retAceite = await FreteFactory.create().aceitar(false, pedido.IdFrete, new MotoristaBLL().pegarAtual().Id);
                            }
                        });

                    }
                }
            }

            task = Task.Factory.StartNew(() =>
            {
                Task.Delay(delay).Wait();
                atualizaPedidosMotoristaAsync().Wait();
            });
        }
    }
}
