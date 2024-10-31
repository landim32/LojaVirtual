using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Endereco.Utils;
using Emagine.GPS.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Model;
using Emagine.Pedido.Factory;
using Emagine.Pedido.Model;
using Emagine.Pedido.Pages;
using Emagine.Produto.Factory;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Pagamento.Pages
{
    public class EntregaMetodoPage : ContentPage
    {
        protected int x = 0, y = 0;

        protected Grid _mainLayout;
        protected MenuButton _entregaButton;
        protected MenuButton _retirarNoLocalButton;

        public PedidoInfo Pedido { get; set; }
        public event EventHandler<PedidoInfo> AoDefinirEntrega;

        public EntregaMetodoPage()
        {
            Title = "Forma de Entrega";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();
            _mainLayout = new Grid
            {
                Margin = new Thickness(10, 10),
                RowSpacing = 10,
                ColumnSpacing = 10
            };

            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _mainLayout.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });

            atualizarBotao();

            Content = _mainLayout;
        }

        protected void adicionarBotao(MenuButton botao) {
            _mainLayout.Children.Add(botao, x, y);
            x++;
            if (x > 1)
            {
                x = 0;
                y++;
            }
        }

        protected virtual void atualizarBotao() {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja.UsaEntregar)
            {
                adicionarBotao(_entregaButton);
            }
            if (loja.UsaRetirar)
            {
                adicionarBotao(_retirarNoLocalButton);
            }
        }

        /*
        private async void abrirHorarioEntrega(PedidoInfo pedido) {
            UserDialogs.Instance.ShowLoading("Carregando horários de entrega...");
            try
            {
                var regraHorario = PedidoHorarioFactory.create();
                var horarios = await regraHorario.listar(Pedido.IdLoja);
                if (horarios.Count > 1)
                {
                    var horarioEntregaPage = new HorarioEntregaPage()
                    {
                        Title = "Horário de Entrega",
                        Horarios = horarios
                    };
                    horarioEntregaPage.AoSelecionar += (s2, horario) =>
                    {
                        Pedido.DiaEntrega = horarioEntregaPage.DiaEntrega;
                        Pedido.HorarioEntrega = horario.Horario;
                        definirEntrega(Pedido);
                    };
                    UserDialogs.Instance.HideLoading();
                    await Navigation.PushAsync(horarioEntregaPage);
                }
                else
                {
                    UserDialogs.Instance.HideLoading();
                    definirEntrega(Pedido);
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                //await DisplayAlert("Erro", erro.Message, "Entendi");
            }
        }
        */

        protected virtual void inicializarComponente() {
            _entregaButton = new MenuButton {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-motorcycle",
                Text = "Receber em\nCasa",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _entregaButton.Click += async (sender, e) => {
                Pedido.Entrega = EntregaEnum.Entrega;
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                /*
                if (usuario.Enderecos.Count == 1)
                {
                    var endereco = usuario.Enderecos[0];
                    Pedido.Cep = endereco.Cep;
                    Pedido.Logradouro = endereco.Logradouro;
                    Pedido.Complemento = endereco.Complemento;
                    Pedido.Numero = endereco.Numero;
                    Pedido.Bairro = endereco.Bairro;
                    Pedido.Cidade = endereco.Cidade;
                    Pedido.Uf = endereco.Uf;
                    Pedido.Latitude = endereco.Latitude;
                    Pedido.Longitude = endereco.Longitude;

                    definirEntrega(Pedido);
                }
                */
                if (usuario.Enderecos.Count > 0)
                {
                    var enderecoListaPage = EnderecoUtils.gerarEnderecoLista((endereco) => {
                        Pedido.Cep = endereco.Cep;
                        Pedido.Logradouro = endereco.Logradouro;
                        Pedido.Complemento = endereco.Complemento;
                        Pedido.Numero = endereco.Numero;
                        Pedido.Bairro = endereco.Bairro;
                        Pedido.Cidade = endereco.Cidade;
                        Pedido.Uf = endereco.Uf;
                        Pedido.Latitude = endereco.Latitude;
                        Pedido.Longitude = endereco.Longitude;

                        //abrirHorarioEntrega(Pedido);
                        definirEntrega(Pedido);
                    });
                    var enderecos = new List<EnderecoInfo>();
                    foreach (var endereco in usuario.Enderecos) {
                        enderecos.Add(endereco);
                    }
                    enderecoListaPage.Enderecos = enderecos;
                    await Navigation.PushAsync(enderecoListaPage);
                }
                else
                {
                    var cepPage = EnderecoUtils.gerarBuscaPorCep(async (endereco) =>
                    {
                        UserDialogs.Instance.ShowLoading("Atualizando endereço...");
                        try
                        {
                            var regraLogin = UsuarioFactory.create();
                            usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                            await regraLogin.alterar(usuario);
                            regraLogin.gravarAtual(usuario);

                            Pedido.Cep = endereco.Cep;
                            Pedido.Logradouro = endereco.Logradouro;
                            Pedido.Complemento = endereco.Complemento;
                            Pedido.Numero = endereco.Numero;
                            Pedido.Bairro = endereco.Bairro;
                            Pedido.Cidade = endereco.Cidade;
                            Pedido.Uf = endereco.Uf;
                            Pedido.Latitude = endereco.Latitude;
                            Pedido.Longitude = endereco.Longitude;

                            var regraHorario = PedidoHorarioFactory.create();
                            var horarios = await regraHorario.listar(Pedido.IdLoja);
                            if (horarios.Count > 1)
                            {
                                var horarioEntregaPage = new HorarioEntregaPage()
                                {
                                    Title = "Horário de Entrega",
                                    Horarios = horarios
                                };
                                horarioEntregaPage.AoSelecionar += (s2, e2) =>
                                {
                                    definirEntrega(Pedido);
                                };
                                UserDialogs.Instance.HideLoading();
                                await Navigation.PushAsync(horarioEntregaPage);
                            }
                            else {
                                UserDialogs.Instance.HideLoading();
                                definirEntrega(Pedido);
                            }
                        }
                        catch (Exception erro)
                        {
                            UserDialogs.Instance.HideLoading();
                            UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                            //await DisplayAlert("Erro", erro.Message, "Entendi");
                        }
                    }, false);
                    await Navigation.PushAsync(cepPage);
                }
            };
            _retirarNoLocalButton = new MenuButton
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-home",
                Text = "Retirar na Loja",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _retirarNoLocalButton.Click += (sender, e) => {
                Pedido.Entrega = EntregaEnum.RetirarNaLoja;
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                if (usuario.Enderecos.Count == 1)
                {
                    var endereco = usuario.Enderecos[0];
                    Pedido.Cep = endereco.Cep;
                    Pedido.Logradouro = endereco.Logradouro;
                    Pedido.Complemento = endereco.Complemento;
                    Pedido.Numero = endereco.Numero;
                    Pedido.Bairro = endereco.Bairro;
                    Pedido.Cidade = endereco.Cidade;
                    Pedido.Uf = endereco.Uf;
                    Pedido.Latitude = endereco.Latitude;
                    Pedido.Longitude = endereco.Longitude;
                }
                definirEntrega(Pedido);
            };
        }

        protected virtual void definirEntrega(PedidoInfo pedido) {
            AoDefinirEntrega?.Invoke(this, Pedido);
        }
    }
}