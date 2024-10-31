using System;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Endereco.Controls;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class EnderecoEntregaPage : ContentPage
    {
        private EnderecoMapaForm _enderecoForm;
        private Button _salvarButton;
        private EnderecoInfo _Info;
        public event EventHandler<EnderecoInfo> Finished;

        public EnderecoEntregaPage(EnderecoInfo info = null)
        {
            _Info = info;
            Title = "Endereço de entrega";
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = 10,
                Children = {
                    _enderecoForm,
                    _salvarButton
                }
            };
        }

        private void inicializarComponente()
        {
            _enderecoForm = new EnderecoMapaForm
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };
            _enderecoForm.Endereco = _Info;

            _salvarButton = new Button
            {
                Text = "Salvar",
                Style = Estilo.Current[Estilo.BTN_PADRAO]
            };
            _salvarButton.Clicked += async (sender, e) =>
            {
                if (string.IsNullOrEmpty(_enderecoForm.Endereco.Cep))
                {
                    await DisplayAlert("Atenção", "Preencha o cep.", "Entendi");
                    return;
                }
                if (string.IsNullOrEmpty(_enderecoForm.Endereco.Uf))
                {
                    await DisplayAlert("Atenção", "Preencha a UF.", "Entendi");
                    return;
                }
                if (string.IsNullOrEmpty(_enderecoForm.Endereco.Cidade))
                {
                    await DisplayAlert("Atenção", "Preencha a cidade.", "Entendi");
                    return;
                }
                if (string.IsNullOrEmpty(_enderecoForm.Endereco.Logradouro))
                {
                    await DisplayAlert("Atenção", "Preencha a rua.", "Entendi");
                    return;
                }
                if (string.IsNullOrEmpty(_enderecoForm.Endereco.Numero))
                {
                    await DisplayAlert("Atenção", "Preencha o numero.", "Entendi");
                    return;
                }
                Finished?.Invoke(this, _enderecoForm.Endereco);
                Navigation.PopAsync();
            };

        }
    }
}

