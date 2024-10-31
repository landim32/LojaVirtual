using Acr.UserDialogs;
using Emagine.Base.Pages;
using Emagine.Endereco.Model;
using Emagine.Frete.Pages;
using Emagine.GPS.Model;
using Emagine.GPS.Utils;
using Emagine.Login.Factory;
using Emagine.Mapa.Model;
using Emagine.Mapa.Utils;
using Emagine.Pagamento.Pages;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Model;
using Emagine.Pedido.Pages;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Pedido.Utils
{
    public static class PedidoUtils
    {
        public static async Task gerarMeuPedido() {
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                var regraUsuario = UsuarioFactory.create();
                var usuarioAtual = regraUsuario.pegarAtual();

                var regraPedido = PedidoFactory.create();
                var pedidos = await regraPedido.listar(usuarioAtual.Id);
                var pedidoListaPage = new PedidoListaPage
                {
                    Title = "Meus Pedidos",
                    Pedidos = pedidos
                };
                pedidoListaPage.AoSelecionar += (sender, pedido) => {
                    var pedidoPage = new PedidoPage
                    {
                        Pedido = pedido
                    };
                    ((Page)sender).Navigation.PushAsync(pedidoPage);
                };
                UserDialogs.Instance.HideLoading();
                ((RootPage)App.Current.MainPage).PaginaAtual = pedidoListaPage;
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        public static EntregaMetodoPage gerarEntregaMetodo(Action<PedidoInfo> aoDefinirEntrega) {
            var entregaPage = EntregaMetodoPageFactory.create();
            entregaPage.Title = "Selecione a forma de entrega";
            entregaPage.AoDefinirEntrega += async (sender, pedido) => {
                if (pedido.Entrega == EntregaEnum.RetiradaMapeada) {
                    if (!await GPSUtils.Current.inicializar()) {
                        await App.Current.MainPage.DisplayAlert("Erro", "Ative seu GPS ou escolha outro método de entrega.", "Entendi");
                        return;
                    }
                }
                aoDefinirEntrega?.Invoke(pedido);
            };
            return entregaPage;
        }

        public static PedidoInfo gerar(IList<ProdutoInfo> produtos, EnderecoInfo endereco = null) {
            var regraUsuario = UsuarioFactory.create();
            var regraLoja = LojaFactory.create();

            var usuario = regraUsuario.pegarAtual();
            var loja = regraLoja.pegarAtual();

            var pedido = new PedidoInfo {
                IdLoja = loja.Id,
                IdUsuario = (usuario != null) ? usuario.Id : 0,
                ValorFrete = 0,
                TrocoPara = 0,
                FormaPagamento = FormaPagamentoEnum.Dinheiro,
                Situacao = Model.SituacaoEnum.AguardandoPagamento
            };
            if (endereco != null) {
                pedido.Cep = endereco.Cep;
                pedido.Logradouro = endereco.Logradouro;
                pedido.Complemento = endereco.Complemento;
                pedido.Numero = endereco.Numero;
                pedido.Bairro = endereco.Bairro;
                pedido.Cidade = endereco.Cidade;
                pedido.Uf = endereco.Uf;
                pedido.Latitude = endereco.Latitude;
                pedido.Longitude = endereco.Longitude;
            }
            foreach (var produto in produtos) {
                pedido.Itens.Add(new PedidoItemInfo
                {
                    IdProduto = produto.Id,
                    Produto = produto,
                    Quantidade = produto.QuantidadeCarrinho
                });
            }
            return pedido;
        }
    }
}
