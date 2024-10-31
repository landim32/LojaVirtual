using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.Controls;
using Emagine.Endereco.Model;
using Emagine.Login.Factory;
using Emagine.Login.Pages;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Endereco.Pages
{
    public class CepPage : ContentPage
    {
        private StackLayout _mainLayout;
        private CepEntry _cepEntry;
        private Button _enderecoButton;
        private Button _buscaEnderecoButton;
        private Button _loginButton;

        public event EventHandler<EnderecoInfo> AoSelecionar;
        public event EventHandler AoBuscar;
        public event EventHandler AoLogar;

        public bool UsaBotaoLogin
        {
            get
            {
                return _mainLayout.Children.Contains(_loginButton);
            }
            set
            {
                if (value)
                {
                    if (!_mainLayout.Children.Contains(_loginButton))
                    {
                        _mainLayout.Children.Add(_loginButton);
                    }
                }
                else
                {
                    if (_mainLayout.Children.Contains(_loginButton))
                    {
                        _mainLayout.Children.Remove(_loginButton);
                    }
                }
            }
        }

        public CepPage()
        {
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();

            _mainLayout = new StackLayout
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Fill,
                Margin = new Thickness(20, 30),
                Children = {
                    _cepEntry,
                    _enderecoButton,
                    _buscaEnderecoButton,
                    _loginButton
                }
            };
            Content = _mainLayout;
            _cepEntry.Focus();
        }

        private void inicializarComponente()
        {
            _cepEntry = new CepEntry
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Placeholder = "Preencha seu CEP",
                Style = Estilo.Current[Estilo.ENTRY_PADRAO],
                FontSize = 30,
                HorizontalTextAlignment = TextAlignment.Center
            };
            _cepEntry.TextChanged += (sender, e) => {
                var cepApenasNumero = _cepEntry.TextOnlyNumber;
                if (cepApenasNumero.Length >= 8)
                {
                    pegarEnderecoPorCep(cepApenasNumero);
                }
            };
            _enderecoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PRINCIPAL],
                Text = "Buscar"
            };
            _enderecoButton.Clicked += (sender, e) => {
                var cepApenasNumero = _cepEntry.TextOnlyNumber;
                if (string.IsNullOrEmpty(cepApenasNumero))
                {
                    DisplayAlert("Aviso", "Preencha o CEP.", "Fechar");
                    return;
                }
                pegarEnderecoPorCep(cepApenasNumero);
            };
            _buscaEnderecoButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_PADRAO],
                Text = "Não lembro do meu CEP"
            };
            _buscaEnderecoButton.Clicked += (sender, e) => {
                //var ufPage = new UfListaPage();
                //Navigation.PushAsync(ufPage);
                AoBuscar?.Invoke(this, new EventArgs());
            };
            _loginButton = new Button
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO],
                Text = "Já possuo uma conta"
            };
            _loginButton.Clicked += (sender, e) => {
                AoLogar?.Invoke(this, new EventArgs());
                //Navigation.PushAsync(LoginUtils.gerarLoginOld());
            };
        }

        private async void pegarEnderecoPorCep(string cep)
        {
            UserDialogs.Instance.ShowLoading("Buscando...");
            try
            {
                var regraCep = CepFactory.create();
                var endereco = await regraCep.pegarPorCep(cep);
                UserDialogs.Instance.HideLoading();
                if (endereco != null)
                {
                    AoSelecionar?.Invoke(this, endereco);
                }
                else
                {
                    string mensagem = string.Format("Nenhum endereço encontrado com o CEP {0}.", cep);
                    await DisplayAlert("Aviso", mensagem, "Fechar");
                }
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                await DisplayAlert("Erro", erro.Message, "Fechar");
            }
        }
    }
}
