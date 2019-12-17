using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.GPS.Utils;
using Emagine.Login.Factory;
using Emagine.Pagamento.Pages;
using Emagine.Pedido.Model;
using Emagine.Produto.Factory;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Loja.Pages
{
    public class CustomEntregaMetodoPage: EntregaMetodoPage
    {
        protected MenuButton _retirarMapeadaButton;

        protected override void inicializarComponente()
        {
            base.inicializarComponente();
            _entregaButton.Text = "Delivery";
            _retirarMapeadaButton = new MenuButton
            {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.Start,
                Icon = "fa-map-marker",
                Text = "Pão no\nSinal",
                Style = Estilo.Current[Estilo.BTN_ROOT]
            };
            _retirarMapeadaButton.Click += (s1, e1) => {
                if (!GPSUtils.Current.estaDisponivel())
                {
                    DisplayAlert("Aviso", "Ative a localização por GPS.", "Entendi");
                    return;
                }
                Pedido.Entrega = EntregaEnum.RetiradaMapeada;
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
                var entregaForm = new EntregaFormPage
                {
                    Title = "Detalhes da Entrega",
                    Pedido = Pedido
                };
                entregaForm.AoDefinirEntrega += (s2, e2) =>
                {
                    definirEntrega(Pedido);
                };
                Navigation.PushAsync(entregaForm);
            };
        }

        protected override void atualizarBotao()
        {
            base.atualizarBotao();
            if (GPSUtils.UsaLocalizacao)
            {
                adicionarBotao(_retirarMapeadaButton);
            }
        }
    }
}
