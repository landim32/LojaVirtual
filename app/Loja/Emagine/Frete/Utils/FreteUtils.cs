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
        public static void gerarPagamento(FreteInfo frete, Action<FreteInfo> aoEfetuarPagamento) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var pgtoInicial = new PagamentoInfo
            {
                Cpf = usuario.CpfCnpj,
                IdUsuario = usuario.Id
            };
            pgtoInicial.Itens.Add(new PagamentoItemInfo
            {
                Descricao = "Frete",
                Quantidade = 1,
                Valor = frete.Preco
            });
            var metodoPagamento = new PagamentoMetodoPage
            {
                Pagamento = pgtoInicial,
                UsaCredito = true,
                UsaDebito = false,
                UsaBoleto = false,
                UsaDinheiro = true
            };
            metodoPagamento.AoEfetuarPagamento += async (sender, pagamento) =>
            {
                frete.IdPagamento = pagamento.IdPagamento;
                UserDialogs.Instance.ShowLoading("Processando pagamento...");
                try
                {
                    var regraFrete = FreteFactory.create();
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
                    var id_frete = frete.Id;
                    if (id_frete > 0)
                    {
                        await regraFrete.alterar(frete);

                    }
                    else {
                        id_frete = await regraFrete.inserir(frete);
                    }
                    var novoFrete = await regraFrete.pegar(id_frete);
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
            ((RootPage)App.Current.MainPage).PushAsync(metodoPagamento);
        }
    }
}
