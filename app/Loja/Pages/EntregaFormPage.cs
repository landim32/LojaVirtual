using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Pedido.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;
using Xfx;

namespace Loja.Pages
{
	public class EntregaFormPage : ContentPage
	{
        private const string PLACA = "PLACA";

        private XfxEntry _PlacaEntry;
        private Button _ContinuarButton;

        public PedidoInfo Pedido { get; set; }
        public event EventHandler<PedidoInfo> AoDefinirEntrega;

        public EntregaFormPage ()
		{
            Title = "Detalhes da Entrega";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();
            carregarDado();

			Content = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Padding = 5,
                Children = {
					_PlacaEntry,
                    _ContinuarButton
				}
			};
		}

        private void carregarDado() {
            var regraUsuario = UsuarioFactory.create();
            var usuario = regraUsuario.pegarAtual();
            _PlacaEntry.Text = (
                from p in usuario.Preferencias
                where p.Chave == PLACA
                select p.Valor
            ).FirstOrDefault();
        }

        private void inicializarComponente() {
            _PlacaEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Placa do Carro",
            };
            _ContinuarButton = new Button {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Continuar"
            };
            _ContinuarButton.Clicked += async (sender, e) => {
                if (string.IsNullOrEmpty(_PlacaEntry.Text)) {
                    await DisplayAlert("Aviso", "Preencha a placa do seu carro.", "Entendi");
                }
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                var placa = (
                    from p in usuario.Preferencias
                    where p.Chave == PLACA
                    select p.Valor
                ).FirstOrDefault();
                if (string.Compare(_PlacaEntry.Text, placa, true) != 0)
                {
                    UserDialogs.Instance.ShowLoading("Carregando...");
                    try
                    {
                        usuario = await regraUsuario.pegar(usuario.Id);
                        var preferencia = (
                            from p in usuario.Preferencias
                            where p.Chave == PLACA
                            select p
                        ).FirstOrDefault();
                        if (preferencia != null)
                        {
                            preferencia.Valor = _PlacaEntry.Text;
                        }
                        else {
                            usuario.Preferencias.Add(new UsuarioPreferenciaInfo {
                                IdUsuario = usuario.Id,
                                Chave = PLACA,
                                Valor = _PlacaEntry.Text
                            });
                        }
                        await regraUsuario.alterar(usuario);
                        UserDialogs.Instance.HideLoading();
                    }
                    catch (Exception erro)
                    {
                        UserDialogs.Instance.HideLoading();
                        UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                    }
                }
                AoDefinirEntrega?.Invoke(this, Pedido);
            };
        }
	}
}