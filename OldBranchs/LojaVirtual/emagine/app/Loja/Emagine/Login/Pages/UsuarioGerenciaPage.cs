﻿using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Endereco.Model;
using Emagine.Endereco.Pages;
using Emagine.Endereco.Utils;
using Emagine.Login.Factory;
using Emagine.Login.Model;
using Emagine.Login.Pages;
using Emagine.Login.Utils;
using Emagine.Pagamento.Factory;
using Emagine.Pagamento.Pages;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
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

        protected virtual void inicializarComponente() {
            _usuarioPage = UsuarioFormPageFactory.create();
            _usuarioPage.Title = "Dados";
            _usuarioPage.Gravar = true;
            _usuarioPage.AoCadastrar += (sender, e) => {
                //UserDialogs.Instance.Alert("Dados alterados com sucesso.", "Aviso", "Fechar");
                _usuarioPage.DisplayAlert("Aviso", "Dados alterados com sucesso.", "Fechar");
            };
            _enderecoListaPage = EnderecoUtils.gerarEnderecoLista(null);
            _cartaoListaPage = new CartaoListaPage {
                Title = "Meus cartões"
            };
        }

        protected async virtual Task atualizandoDado() {
            var regraUsuario = UsuarioFactory.create();
            var usuarioAtual = regraUsuario.pegarAtual();
            if (usuarioAtual == null) {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert("Não está logado.", "Erro", "Fechar");
                return;
            }
            var usuario = await regraUsuario.pegar(usuarioAtual.Id);
            regraUsuario.gravarAtual(usuario);
            if (App.Current.MainPage is RootPage)
            {
                ((RootPage)App.Current.MainPage).atualizarMenu();
            }
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
            return;
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
                await atualizandoDado();
                _carregado = true;
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
            }
        }
    }
}