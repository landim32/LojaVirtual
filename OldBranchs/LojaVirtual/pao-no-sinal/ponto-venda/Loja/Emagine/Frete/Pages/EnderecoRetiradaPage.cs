using System;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Endereco.Controls;
using Xamarin.Forms;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Acr.UserDialogs;
using System.Linq;

namespace Emagine.Frete.Pages
{
    public class EnderecoRetiradaPage : ContentPage
    {
        private EnderecoMapaForm _enderecoForm;
        private Button _salvarButton;
        private UsuarioEnderecoInfo _Info;
        public event EventHandler<EnderecoInfo> Finished;

        public EnderecoRetiradaPage(UsuarioEnderecoInfo info = null)
        {
            _Info = info;
            Title = "Endereço de retirada";
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
                if(string.IsNullOrEmpty(_enderecoForm.Endereco.Cep)){
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

                UserDialogs.Instance.ShowLoading("Enviando...");
                var usuarioFactor = UsuarioFactory.create();
                var usuario = usuarioFactor.pegarAtual();
                if(_Info != null)
                {
                    var end = UsuarioEnderecoInfo.clonar(_enderecoForm.Endereco);
                    var endOld = usuario.Enderecos.Where(x => x.Id == _Info.Id).FirstOrDefault();
                    end.Id = endOld.Id;
                    usuario.Enderecos.Remove(endOld);
                    usuario.Enderecos.Add(end);    
                }
                else
                {
                    usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(_enderecoForm.Endereco));    
                }
                await usuarioFactor.alterar(usuario);
                var usuarioCadastrado = await usuarioFactor.pegar(usuario.Id);
                usuarioFactor.gravarAtual(usuarioCadastrado);
                UserDialogs.Instance.HideLoading();

                Finished?.Invoke(this, _enderecoForm.Endereco);
                Navigation.PopAsync();
            };

        }
    }
}

