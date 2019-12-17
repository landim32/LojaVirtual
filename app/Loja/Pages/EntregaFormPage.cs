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
        private const string MODELO = "MODELO";
        private const string COR = "COR";

        private XfxEntry _PlacaEntry;
        private XfxEntry _modeloEntry;
        private XfxEntry _corEntry;
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
                    _modeloEntry,
                    _corEntry,
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
            _modeloEntry.Text = (
                from p in usuario.Preferencias
                where p.Chave == MODELO
                select p.Valor
            ).FirstOrDefault();
            _corEntry.Text = (
                from p in usuario.Preferencias
                where p.Chave == COR
                select p.Valor
            ).FirstOrDefault();
        }

        private void adicionarPreferencia(UsuarioInfo usuario, string chave, string valor) {
            var preferencia = (
                from p in usuario.Preferencias
                where p.Chave == chave
                select p
            ).FirstOrDefault();
            if (preferencia != null) {
                preferencia.Valor = valor;
            }
            else {
                usuario.Preferencias.Add(new UsuarioPreferenciaInfo {
                    IdUsuario = usuario.Id,
                    Chave = chave,
                    Valor = valor
                });
            }
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
            _modeloEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Modelo do Carro",
            };
            _corEntry = new XfxEntry
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                ErrorDisplay = ErrorDisplay.None,
                Style = Estilo.Current[Estilo.ENTRY_MATERIAL],
                Placeholder = "Cor do Carro",
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
                    return;
                }
                if (string.IsNullOrEmpty(_modeloEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a placa do seu carro.", "Entendi");
                    return;
                }
                if (string.IsNullOrEmpty(_corEntry.Text))
                {
                    await DisplayAlert("Aviso", "Preencha a cor do seu carro.", "Entendi");
                    return;
                }
                var regraUsuario = UsuarioFactory.create();
                var usuario = regraUsuario.pegarAtual();
                /*
                var placa = (
                    from p in usuario.Preferencias
                    where p.Chave == PLACA
                    select p.Valor
                ).FirstOrDefault();
                */
                //if (string.Compare(_PlacaEntry.Text, placa, true) != 0) {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    usuario = await regraUsuario.pegar(usuario.Id);
                    adicionarPreferencia(usuario, PLACA, _PlacaEntry.Text);
                    adicionarPreferencia(usuario, MODELO, _modeloEntry.Text);
                    adicionarPreferencia(usuario, COR, _corEntry.Text);
                    await regraUsuario.alterar(usuario);
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                }
                //}
                AoDefinirEntrega?.Invoke(this, Pedido);
            };
        }
	}
}