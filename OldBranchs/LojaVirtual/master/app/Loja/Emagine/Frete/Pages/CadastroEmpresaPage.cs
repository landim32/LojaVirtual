using Acr.UserDialogs;
using Emagine.Base.Controls;
using Emagine.Base.Estilo;
using Emagine.Base.Utils;
using Emagine.Endereco.Controls;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class CadastroEmpresaPage : ContentPage
    {
        private UsuarioInfo _usuario;

        private Entry _nomeEmpresaEntry;
        private EnderecoListaForm _enderecoForm;
        private Button _CadastroButton;

        public event EventHandler<UsuarioInfo> AoCompletar;

        public CadastroEmpresaPage(UsuarioInfo usuario)
        {
            Title = "Criando nova conta";

            _usuario = usuario;

            inicializarComponente();

            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = new StackLayout
                {
                    Orientation = StackOrientation.Vertical,
                    VerticalOptions = LayoutOptions.Fill,
                    HorizontalOptions = LayoutOptions.Fill,
                    Padding = 5,
                    Children = {
                        _nomeEmpresaEntry,
                        _enderecoForm,
                        _CadastroButton
                    }
                }
            };
        }

        private void inicializarComponente()
        {
            _nomeEmpresaEntry = new Entry
            {
                Placeholder = "Nome da Empresa",
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };

            _enderecoForm = new EnderecoListaForm
            {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill
            };

            _CadastroButton = new Button()
            {
                Text = "CADASTRAR",
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                Style = Estilo.Current[Estilo.BTN_SUCESSO]
            };
            _CadastroButton.Clicked += async (sender, e) =>
            {
                var regraUsuario = UsuarioFactory.create();
                _usuario.Preferencias.Add(new UsuarioPreferenciaInfo {
                    Chave = "NOME_EMPRESA",
                    Valor = _nomeEmpresaEntry.Text
                });
                _usuario.Enderecos = new List<UsuarioEnderecoInfo>();
                foreach(var endereco in _enderecoForm.Enderecos)
                {
                    _usuario.Enderecos.Add(UsuarioEnderecoInfo.clonar(endereco));
                }

                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    if (_usuario.Id > 0)
                    {
                        await regraUsuario.alterar(_usuario);
                    }
                    else
                    {
                        _usuario.Id = await regraUsuario.inserir(_usuario);
                    }
                    var usuarioCadastrado = await regraUsuario.pegar(_usuario.Id);

                    regraUsuario.gravarAtual(usuarioCadastrado);
                    UserDialogs.Instance.HideLoading();
                    if (usuarioCadastrado != null)
                    {
                        regraUsuario.gravarAtual(usuarioCadastrado);
                        AoCompletar?.Invoke(this, _usuario);
                    }
                    else
                    {
                        string mensagem = string.Format("Nenhum usuário encontrado com o ID {0}.", _usuario.Id);
                        await DisplayAlert("Aviso", mensagem, "Fechar");
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }

            };

        }
    }
}