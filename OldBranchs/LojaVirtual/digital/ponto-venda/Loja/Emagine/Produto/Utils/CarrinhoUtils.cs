using Acr.UserDialogs;
using Emagine.Base.Pages;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Login.Utils;
using Emagine.Pagamento.Model;
using Emagine.Pagamento.Pages;
using Emagine.Pagamento.Utils;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Model;
using Emagine.Pedido.Pages;
using Emagine.Pedido.Utils;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Utils
{
    public static class CarrinhoUtils
    {
        public static Page gerarCarrinho(Action<PedidoInfo> AoSelecionarEndereco)
        {
            var carrinhoPage = new CarrinhoPage
            {
                Title = "Meu carrinho"
            };
            carrinhoPage.AoFinalizar += (s1, produtos) =>
            {
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                if (usuario != null && usuario.Id > 0)
                {
                    if (usuario.Enderecos.Count > 0)
                    {
                        var enderecoListaPage = EnderecoUtils.gerarEnderecoLista((endereco) =>
                        {
                            AoSelecionarEndereco(PedidoUtils.gerar(produtos, endereco));
                        });
                        enderecoListaPage.Title = "Local de entrega";
                        ((Page)s1).Navigation.PushAsync(enderecoListaPage);
                    }
                    else
                    {
                        var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) =>
                        {
                            usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                            var regraLogin = UsuarioFactory.create();
                            regraLogin.gravarAtual(usuario);

                            AoSelecionarEndereco(PedidoUtils.gerar(produtos, endereco));
                        });
                        ((Page)s1).Navigation.PushAsync(cepPage);
                    }

                }
                else
                {
                    if (usuario == null)
                    {
                        usuario = new UsuarioInfo();
                    }
                    var usuarioCadastroPage = UsuarioFormPageFactory.create();
                    usuarioCadastroPage.Title = "Cadastre-se";
                    usuarioCadastroPage.Gravar = true;
                    usuarioCadastroPage.Usuario = usuario;
                    usuarioCadastroPage.AoCadastrar += (s2, usuario2) =>
                    {
                        var cepPage = EnderecoUtils.gerarBuscaPorCep((endereco) =>
                        {
                            usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                            var regraLogin = UsuarioFactory.create();
                            regraLogin.gravarAtual(usuario);

                            AoSelecionarEndereco(PedidoUtils.gerar(produtos, endereco));
                        });
                        ((Page)s2).Navigation.PushAsync(cepPage);
                    };
                    ((Page)s1).Navigation.PushAsync(usuarioCadastroPage);
                }
            };
            return carrinhoPage;
        }

        public static Page gerarCarrinhoParaPagamento()
        {
            return gerarCarrinho((pedido) => {
                var pagamento = PagamentoUtils.gerar(pedido);
                var regraLoja = LojaFactory.create();
                var loja = regraLoja.pegarAtual();
                var pagamentoMetodoPage = new PagamentoMetodoPage {
                    Title = "Forma de Pagamento",
                    Pagamento = pagamento,
                    UsaCredito = loja.AceitaCreditoOnline,
                    UsaDebito = loja.AceitaDebitoOnline,
                    UsaDinheiro = loja.AceitaDinheiro,
                    UsaBoleto = loja.AceitaBoleto
                };
                ((RootPage)App.Current.MainPage).PushAsync(pagamentoMetodoPage);
            });
        }

        public static Page gerarCarrinhoParaEntrega()
        {
            var carrinhoPage = new CarrinhoPage
            {
                Title = "Meu carrinho"
            };
            carrinhoPage.AoFinalizar += (s1, produtos) =>
            {
                LoginUtils.carregarUsuario((usuario) => {
                    var metodoEntregaPage = PedidoUtils.gerarEntregaMetodo(async (pedido) => {
                        if (await UserDialogs.Instance.ConfirmAsync("Deseja fechar o pedido?", "Aviso", "Sim", "Não", null)) {
                            var pagamentoMetodoPage = PagamentoUtils.gerarPagamento(async (pagamento) => {
                                UserDialogs.Instance.ShowLoading("Enviando...");
                                try
                                {
                                    pedido.IdPagamento = pagamento.IdPagamento;
                                    switch (pagamento.Tipo)
                                    {
                                        case TipoPagamentoEnum.CreditoOnline:
                                            pedido.FormaPagamento = FormaPagamentoEnum.CreditoOnline;
                                            break;
                                        case TipoPagamentoEnum.DebitoOnline:
                                            pedido.FormaPagamento = FormaPagamentoEnum.DebitoOnline;
                                            break;
                                        case TipoPagamentoEnum.Boleto:
                                            pedido.FormaPagamento = FormaPagamentoEnum.Boleto;
                                            break;
                                        case TipoPagamentoEnum.Dinheiro:
                                            pedido.FormaPagamento = FormaPagamentoEnum.Dinheiro;
                                            pedido.TrocoPara = pagamento.TrocoPara;
                                            break;
                                        case TipoPagamentoEnum.CartaoOffline:
                                            pedido.FormaPagamento = FormaPagamentoEnum.CartaoOffline;
                                            break;
                                    }
                                    switch (pagamento.Situacao)
                                    {
                                        case SituacaoPagamentoEnum.Pago:
                                            pedido.Situacao = Pedido.Model.SituacaoEnum.Preparando;
                                            break;
                                        case SituacaoPagamentoEnum.AguardandoPagamento:
                                            pedido.Situacao = Pedido.Model.SituacaoEnum.AguardandoPagamento;
                                            break;
                                        default:
                                            pedido.Situacao = Pedido.Model.SituacaoEnum.Pendente;
                                            break;
                                    }
                                    var regraPedido = PedidoFactory.create();
                                    int idPedido = pedido.Id;
                                    if (idPedido > 0)
                                    {
                                        await regraPedido.alterar(pedido);
                                    }
                                    else
                                    {
                                        idPedido = await regraPedido.inserir(pedido);
                                    }
                                    var pedidoFechado = await regraPedido.pegar(idPedido);
                                    var regraCarrinho = CarrinhoFactory.create();
                                    regraCarrinho.limpar();

                                    if (pedidoFechado.Entrega == EntregaEnum.RetiradaMapeada) {
                                        AcompanhamentoUtils.iniciarAcompanhamento(pedidoFechado);
                                    }

                                    if (pedidoFechado.Entrega == EntregaEnum.Entrega && pedido.Situacao != Pedido.Model.SituacaoEnum.AguardandoPagamento) {
                                        var regraHorario = PedidoHorarioFactory.create();
                                        var horarios = await regraHorario.listar(pedidoFechado.IdLoja);
                                        if (horarios.Count > 1)
                                        {
                                            var horarioEntregaPage = new HorarioEntregaPage()
                                            {
                                                Title = "Horário de Entrega",
                                                //Pedido = pedidoFechado,
                                                Horarios = horarios
                                            };
                                            horarioEntregaPage.AoSelecionar += async (s2, horario) =>
                                            {
                                                UserDialogs.Instance.ShowLoading("Enviando...");
                                                try
                                                {
                                                    pedidoFechado.DiaEntrega = horarioEntregaPage.DiaEntrega;
                                                    pedidoFechado.HorarioEntrega = horario;
                                                    pedidoFechado.Avisar = false;

                                                    await regraPedido.alterar(pedidoFechado);
                                                    ((RootPage)App.Current.MainPage).PaginaAtual = new PedidoPage
                                                    {
                                                        Pedido = pedidoFechado
                                                    };
                                                    UserDialogs.Instance.HideLoading();
                                                }
                                                catch (Exception erro)
                                                {
                                                    UserDialogs.Instance.HideLoading();
                                                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                                                }
                                            };
                                            UserDialogs.Instance.HideLoading();
                                            ((RootPage)App.Current.MainPage).PushAsync(horarioEntregaPage);
                                        }
                                        else
                                        {
                                            UserDialogs.Instance.HideLoading();
                                            ((RootPage)App.Current.MainPage).PaginaAtual = new PedidoPage {
                                                Pedido = pedidoFechado
                                            };
                                        }
                                    }
                                    else {
                                        UserDialogs.Instance.HideLoading();
                                        ((RootPage)App.Current.MainPage).PaginaAtual = new PedidoPage {
                                            Pedido = pedidoFechado
                                        };
                                    }
                                }
                                catch (Exception erro)
                                {
                                    UserDialogs.Instance.HideLoading();
                                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                                }
                            });
                            var regraLoja = LojaFactory.create();
                            var loja = regraLoja.pegarAtual();
                            pagamentoMetodoPage.UsaCredito = loja.AceitaCreditoOnline;
                            pagamentoMetodoPage.UsaDebito = loja.AceitaDebitoOnline;
                            pagamentoMetodoPage.UsaDinheiro = loja.AceitaDinheiro;
                            pagamentoMetodoPage.UsaBoleto = loja.AceitaBoleto;
                            pagamentoMetodoPage.Pagamento = PagamentoUtils.gerar(pedido);

                            ((RootPage)App.Current.MainPage).PushAsync(pagamentoMetodoPage);
                        }
                    });
                    metodoEntregaPage.Pedido = PedidoUtils.gerar(produtos);
                    ((RootPage)App.Current.MainPage).PushAsync(metodoEntregaPage);
                });
            };
            return carrinhoPage;
        }
    }
}
