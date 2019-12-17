using Acr.UserDialogs;
using Emagine.Base.Pages;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Emagine.Login.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Frete.Utils
{
    public static class FreteUtils
    {
        private static PagamentoInfo gerarPagamentoInfo(FreteInfo frete)
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var pagamento = new PagamentoInfo
            {
                Cpf = usuario.CpfCnpj,
                IdUsuario = usuario.Id
            };
            pagamento.Itens.Add(new PagamentoItemInfo
            {
                Descricao = "Frete",
                Quantidade = 1,
                Valor = frete.Preco
            });
            return pagamento;
        }

        private static async Task<FreteInfo> processarFrete(FreteInfo frete, PagamentoInfo pagamento) {
            var regraFrete = FreteFactory.create();
            if (pagamento.Tipo == TipoPagamentoEnum.Dinheiro) {
                frete.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
            }
            else
            {
                switch (pagamento.Situacao)
                {
                    case SituacaoPagamentoEnum.Pago:
                        frete.Situacao = FreteSituacaoEnum.ProcurandoMotorista;
                        break;
                    case SituacaoPagamentoEnum.Cancelado:
                        frete.Situacao = FreteSituacaoEnum.Cancelado;
                        break;
                    default:
                        frete.Situacao = FreteSituacaoEnum.AguardandoPagamento;
                        break;
                }
            }
            var id_frete = frete.Id;
            if (id_frete > 0)
            {
                await regraFrete.alterar(frete);
            }
            else
            {
                id_frete = await regraFrete.inserir(frete);
            }
            return await regraFrete.pegar(id_frete);
        }

        public static CartaoPage gerarPagamentoCredito(FreteInfo frete, Action<FreteInfo> aoEfetuarPagamento) {
            var pagamento = gerarPagamentoInfo(frete);
            var cartaoPage = new CartaoPage {
                Pagamento = pagamento,
                UsaCredito = true,
                UsaDebito = false
            };
            cartaoPage.AoEfetuarPagamento += async (sender, novoPagamento) => {
                frete.IdPagamento = novoPagamento.IdPagamento;
                UserDialogs.Instance.ShowLoading("Processando pagamento...");
                try
                {
                    var novoFrete = await processarFrete(frete, novoPagamento);
                    UserDialogs.Instance.HideLoading();
                    aoEfetuarPagamento?.Invoke(novoFrete);
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
                UserDialogs.Instance.HideLoading();
            };
            return cartaoPage;
        }

        public static PagamentoMetodoPage gerarPagamento(FreteInfo frete, Action<FreteInfo> aoEfetuarPagamento) {

            var pagamento = gerarPagamentoInfo(frete);
            var metodoPagamento = new PagamentoMetodoPage
            {
                Pagamento = pagamento,
                UsaCredito = true,
                UsaDebito = false,
                UsaBoleto = false,
                UsaCartaoOffline = false,
                UsaDinheiro = true
            };
            metodoPagamento.AoEfetuarPagamento += async (sender, novoPagamento) =>
            {
                frete.IdPagamento = novoPagamento.IdPagamento;
                UserDialogs.Instance.ShowLoading("Processando pagamento...");
                try
                {
                    var novoFrete = await processarFrete(frete, novoPagamento);
                    UserDialogs.Instance.HideLoading();
                    aoEfetuarPagamento?.Invoke(novoFrete);
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
                UserDialogs.Instance.HideLoading();
            };
            //((RootPage)App.Current.MainPage).PushAsync(metodoPagamento);
            return metodoPagamento;
        }
    }
}
