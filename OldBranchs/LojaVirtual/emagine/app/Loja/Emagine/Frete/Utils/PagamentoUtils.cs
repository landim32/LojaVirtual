using Acr.UserDialogs;
using Emagine;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Plugin.LocalNotifications;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Utils
{
    public static class PagamentoUtils
    {
        private static PagamentoInfo gerarPagamento(MotoristaInfo motorista, FreteInfo frete, CartaoInfo cartao = null)
        {
            var pagamento = new PagamentoInfo
            {
                IdUsuario = motorista.Id,
                Situacao = SituacaoPagamentoEnum.Aberto,
                Tipo = TipoPagamentoEnum.CreditoOnline,
                Observacao = "15% da primeira hora"
            };
            pagamento.Itens.Add(new PagamentoItemInfo
            {
                Descricao = "15% da primeira hora",
                Quantidade = 1,
                //Valor = frete.Preco * 0.15
                Valor = 1
            });
            if (cartao != null)
            {
                pagamento.Bandeira = cartao.Bandeira;
                pagamento.Token = cartao.Token;
                pagamento.NomeCartao = cartao.Nome;
                pagamento.CVV = cartao.CVV;
            }
            return pagamento;
        }

        public static async void efetuarPagamento(MotoristaInfo motorista, FreteInfo frete, Action<FreteInfo> aoEfetuarPagamento)
        {
            UserDialogs.Instance.ShowLoading("Efetuando pagamento...");
            try
            {
                var regraCartao = CartaoFactory.create();
                var cartoes = await regraCartao.listar(motorista.Id);
                if (cartoes != null && cartoes.Count > 0)
                {
                    var pagamento = gerarPagamento(motorista, frete, cartoes[0]);
                    var regraPagamento = PagamentoFactory.create();
                    var retorno = await regraPagamento.pagarComToken(pagamento);
                    if (retorno.Situacao == SituacaoPagamentoEnum.Pago)
                    {
                        pagamento = await regraPagamento.pegar(retorno.IdPagamento);

                        var mensagem = "Foram debitados R$ {0} do seu cartão de crédito.";
                        CrossLocalNotifications.Current.Show("Easy Barcos", string.Format(mensagem, pagamento.ValorTotalStr));

                        var regraFrete = FreteFactory.create();
                        frete = await regraFrete.pegar(frete.Id);
                        frete.IdPagamento = pagamento.IdPagamento;
                        await regraFrete.alterar(frete);
                        UserDialogs.Instance.HideLoading();
                        aoEfetuarPagamento?.Invoke(frete);
                    }
                    else
                    {
                        UserDialogs.Instance.HideLoading();
                        await UserDialogs.Instance.AlertAsync(retorno.Mensagem, "Erro", "Entendi");
                    }
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    var cartaoPage = new CartaoPage
                    {
                        UsaCredito = true,
                        UsaDebito = false,
                        TotalVisivel = true,
                        Pagamento = gerarPagamento(motorista, frete)
                    };
                    cartaoPage.AoEfetuarPagamento += async (s2, pagamento) =>
                    {
                        UserDialogs.Instance.ShowLoading("Atualizando frete...");
                        try
                        {
                            var regraFrete = FreteFactory.create();
                            frete = await regraFrete.pegar(frete.Id);
                            frete.IdPagamento = pagamento.IdPagamento;
                            await regraFrete.alterar(frete);
                            UserDialogs.Instance.HideLoading();
                            aoEfetuarPagamento?.Invoke(frete);
                        }
                        catch (Exception erro)
                        {
                            UserDialogs.Instance.HideLoading();
                            await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                        }
                    };
                    ((RootPage)App.Current.MainPage).PushAsync(cartaoPage);
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
            }
        }
    }
}
