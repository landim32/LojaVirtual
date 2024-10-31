using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Login.Pages
{
    public class UsuarioGerenciaPage : TabbedPage
    {
        private bool _carregado = false;
        private bool _enderecoVisivel = true;
        private UsuarioFormPage _usuarioPage;
        private EnderecoListaPage _enderecoListaPage;
        private CartaoListaPage _cartaoListaPage;

        public bool EnderecoVisivel {
            get {
                return _enderecoVisivel;
            }
            set {
                _enderecoVisivel = value;
                if (_enderecoVisivel) {
                    if (!Children.Contains(_enderecoListaPage)) {
                        Children.Add(_enderecoListaPage);
                    }
                }
                else {
                    if (Children.Contains(_enderecoListaPage))
                    {
                        Children.Remove(_enderecoListaPage);
                    }
                }
            }
        }

        public UsuarioGerenciaPage()
        {
            Title = "Meu cadastro";
            Style = Estilo.Current[Estilo.TELA_PADRAO];

            inicializarComponente();
            Children.Add(_usuarioPage);
            Children.Add(_enderecoListaPage);
            Children.Add(_cartaoListaPage);
        }

        private void inicializarComponente() {
            _usuarioPage = UsuarioFormPageFactory.create();
            _usuarioPage.Title = "Dados";
            _usuarioPage.Gravar = true;
            _usuarioPage.AoCadastrar += (sender, e) => {
                UserDialogs.Instance.Alert("Dados alterados com sucesso.", "Aviso", "Fechar");
            };
            _enderecoListaPage = EnderecoUtils.gerarEnderecoLista(null);
            _cartaoListaPage = new CartaoListaPage {
                Title = "Meus cartões"
            };
        }

        protected async override void OnAppearing()
        {
            base.OnAppearing();
            if (_carregado)
            {
                return;
            }
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                var regraUsuario = UsuarioFactory.create();
                var usuarioAtual = regraUsuario.pegarAtual();
                if (usuarioAtual == null)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert("Não está logado.", "Erro", "Fechar");
                    return;
                }
                var usuario = await regraUsuario.pegar(usuarioAtual.Id);
                regraUsuario.gravarAtual(usuario);
                _usuarioPage.Usuario = usuario;
                var enderecos = new List<EnderecoInfo>();
                foreach (var endereco in usuario.Enderecos)
                {
                    enderecos.Add(endereco);
                }
                _enderecoListaPage.Enderecos = enderecos;

                var regraCartao = CartaoFactory.create();
                var cartoes = await regraCartao.listar(usuario.Id);
                _cartaoListaPage.Cartoes = cartoes;
                _carregado = true;
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }
    }
}