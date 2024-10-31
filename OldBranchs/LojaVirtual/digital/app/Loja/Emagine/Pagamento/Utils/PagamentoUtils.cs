using Emagine.Login.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Pedido.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Emagine.Pagamento.Utils
{
    public static class PagamentoUtils
    {
        public static PagamentoMetodoPage gerarPagamento(Action<PagamentoInfo> aoPagar) {
            var pagamentoMetodoPage = new PagamentoMetodoPage
            {
                Title = "Forma de Pagamento"
            };
            pagamentoMetodoPage.AoEfetuarPagamento += (sender, pagamento) =>
            {
                aoPagar?.Invoke(pagamento);
            };
            return pagamentoMetodoPage;
        }

        public static PagamentoInfo gerar(PedidoInfo pedido) {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            var pagamento = new PagamentoInfo
            {
                IdUsuario = usuario.Id,
                Cpf = usuario.CpfCnpj,
                Cep = pedido.Cep,
                Logradouro = pedido.Logradouro,
                Complemento = pedido.Complemento,
                Numero = pedido.Numero,
                Bairro = pedido.Bairro,
                Cidade = pedido.Cidade,
                Uf = pedido.Uf,
                Situacao = SituacaoPagamentoEnum.Aberto
            };
            foreach (var item in pedido.Itens)
            {
                pagamento.Itens.Add(new PagamentoItemInfo
                {
                    Descricao = item.Produto.Nome,
                    Quantidade = item.Quantidade,
                    Valor = (item.Produto.ValorPromocao > 0) ? item.Produto.ValorPromocao : item.Produto.Valor
                });
            }
            return pagamento;
        }
    }
}
