using Emagine;
using Emagine.Base.Pages;
using Emagine.Base.Utils;
using Emagine.Endereco.Model;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using Loja.Pages;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Loja.Utils
{
    public static class GeralUtils
    {
        public static void inicializar()
        {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            if (usuario != null)
            {
                abrirLoja();
            }
            else {
                var intro1Page = new IntroPage {
                    Imagem = "semaforo.jpg",
                    Texto = 
                        "Parabéns! Você agora poderá usufruir da facilidade de comprar " +
                        "Pão no Sinal! Utilizando a opção de entrega: Pão no Sinal, você " +
                        "conta com a comodidade de receber seus pedidos sem precisar ter o " +
                        "trabalho de estacionar e sair do carro!!",
                };
                intro1Page.AoAvancar += (s1, e1) => {
                    var intro2Page = new IntroPage
                    {
                        Imagem = "telapaonosinal.jpg",
                        Texto = "Para isto, basta escolher a opção de entrega: Pão no Sinal (caso " +
                            "ela esteja ativa naquele momento). E informar se o pagamento será em " +
                            "cartão, vale-refeição ou dinheiro"
                    };
                    intro2Page.AoAvancar += (s2, e2) => {
                        var intro3Page = new IntroPage
                        {
                            Imagem = "teladetalhe.jpg",
                            Texto = "Você precisará informar também o modelo, a placa e a cor do seu " +
                                    "carro, para que nossos entregadores o localizem no trânsito."
                        };
                        intro3Page.AoAvancar += (s3, e3) => {
                            var intro4Page = new IntroPage
                            {
                                Imagem = "semaforo.jpg",
                                Texto = "Ao confirmar o pedido, você precisa habilitar o envio de sua " +
                                        "localização em seu celular Então, é só aguardar e receber seu pedido " +
                                        "no conforto do seu carro.",
                            };
                            intro4Page.AoAvancar += (s4, e4) => {
                                abrirLoja();
                            };
                            intro3Page.Navigation.PushAsync(intro4Page);
                        };
                        intro2Page.Navigation.PushAsync(intro3Page);
                    };
                    intro1Page.Navigation.PushAsync(intro2Page);
                };
                intro1Page.Appearing += (s, e) => {
                    PermissaoUtils.pedirPermissao();
                };
                App.Current.MainPage = new NavigationPage(intro1Page);
            }
        }

        public static IntroPage gerarSobre()
        {
            var intro1Page = new IntroPage
            {
                Imagem = "semaforo.jpg",
                Texto =
                    "Parabéns! Você agora poderá usufruir da facilidade de comprar " +
                    "Pão no Sinal! Utilizando a opção de entrega: Pão no Sinal, você " +
                    "conta com a comodidade de receber seus pedidos sem precisar ter o " +
                    "trabalho de estacionar e sair do carro!!",
            };
            intro1Page.AoAvancar += (s1, e1) => {
                var intro2Page = new IntroPage
                {
                    Imagem = "telapaonosinal.jpg",
                    Texto = "Para isto, basta escolher a opção de entrega: Pão no Sinal (caso " +
                        "ela esteja ativa naquele momento). E informar se o pagamento será em " +
                        "cartão, vale-refeição ou dinheiro"
                };
                intro2Page.AoAvancar += (s2, e2) => {
                    var intro3Page = new IntroPage
                    {
                        Imagem = "teladetalhe.jpg",
                        Texto = "Você precisará informar também o modelo, a placa e a cor do seu " +
                                "carro, para que nossos entregadores o localizem no trânsito."
                    };
                    intro3Page.AoAvancar += (s3, e3) => {
                        var intro4Page = new IntroPage
                        {
                            Imagem = "semaforo.jpg",
                            Texto = "Ao confirmar o pedido, você precisa habilitar o envio de sua " +
                                    "localização em seu celular Então, é só aguardar e receber seu pedido " +
                                    "no conforto do seu carro.",
                            BotaoExibir = false
                        };
                        /*
                        intro4Page.AoAvancar += (s4, e4) => {
                            intro4Page.Navigation.PopToRootAsync();
                        };
                        */
                        intro3Page.Navigation.PushAsync(intro4Page);
                    };
                    intro2Page.Navigation.PushAsync(intro3Page);
                };
                intro1Page.Navigation.PushAsync(intro2Page);
            };
            return intro1Page;
        }

        public static void abrirLoja() {
            carregarUsuario(async (usuario) => {
                var lojaPage = await LojaUtils.gerarLoja();
                lojaPage.Appearing += (s3, e3) => {
                    PermissaoUtils.pedirPermissao();
                };
                if (lojaPage is ProdutoListaPage) {
                    App.Current.MainPage = App.gerarRootPage(lojaPage);
                }
                else {
                    App.Current.MainPage = new NavigationPage(lojaPage);
                }
            });
        }

        public static void carregarUsuario(Action<UsuarioInfo> aoAvancar) {
            LoginUtils.carregarUsuario((usuario) => {
                if (usuario.Enderecos.Count > 0) {
                    aoAvancar(usuario);
                }
                else
                {
                    var cepPage = EnderecoUtils.gerarBuscaPorCep(async (endereco) => {
                        usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                        var regraUsuario = UsuarioFactory.create();
                        if (usuario.Id > 0)
                        {
                            await regraUsuario.alterar(usuario);
                            usuario = await regraUsuario.pegar(usuario.Id);
                        }
                        else
                        {
                            var idUsuario = await regraUsuario.inserir(usuario);
                            usuario = await regraUsuario.pegar(usuario.Id);
                        }
                        regraUsuario.gravarAtual(usuario);
                        aoAvancar(usuario);
                    });
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).atualizarMenu();
                        ((RootPage)App.Current.MainPage).PushAsync(cepPage);
                    }
                    else {
                        App.Current.MainPage.Navigation.PushAsync(cepPage);
                    }
                    
                }
            }, false);
        }

        /*
        public static Page gerarTermoDeUso(Action<UsuarioInfo> aoCadastrar) {
            var termoConcordanciaPage = new DocumentoPage
            {
                Title = "TERMO DE CONCORDÂNCIA",
                NomeArquivo = "termo_concordancia.html"
            };
            termoConcordanciaPage.AoConfirmar += async (s2, e2) => {
                //aoCadastrar();
            };
            termoConcordanciaPage.AoNegar += (s3, e3) => {
                ((Page)s3).Navigation.PopAsync();
            };
            return termoConcordanciaPage;
        }
        */
    }
}
