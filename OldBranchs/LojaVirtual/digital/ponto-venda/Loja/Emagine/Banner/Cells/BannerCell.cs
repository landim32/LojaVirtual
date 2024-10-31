using Acr.UserDialogs;
using Emagine.Banner.Factory;
using Emagine.Banner.Model;
using Emagine.Base.Pages;
using Emagine.Endereco.Utils;
using Emagine.Produto.Factory;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Emagine.Banner.Cells
{
    public class BannerCell: ContentView
    {
        private Image _banner;

        public BannerPecaInfo Banner {
            get {
                return (BannerPecaInfo)BindingContext;
            }
        }

        public BannerCell() {
            inicializarComponente();
            //View = _Banner;
            //Content = _Banner;
            //BackgroundColor = Color.Blue;
            Content = _banner;
        }

        private void inicializarComponente() {
            _banner = new Image {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Aspect = Aspect.AspectFill,
                //BackgroundColor = Color.Yellow
            };
            _banner.SetBinding(Image.SourceProperty, new Binding("ImagemUrl"));
            var tapGestureRecognizer = new TapGestureRecognizer();
            tapGestureRecognizer.Tapped += OnClicked;
            _banner.GestureRecognizers.Add(tapGestureRecognizer);
        }

        protected void abrirUrl(BannerPecaInfo peca) {
            if (string.IsNullOrEmpty(peca.Url))
            {
                UserDialogs.Instance.AlertAsync("A url não foi informada!", "Erro", "Entendi");
                return;
            }
            Device.OpenUri(new Uri(peca.Url));
        }

        protected async void abrirLoja(BannerPecaInfo peca) {
            if (!(peca.IdLoja > 0))
            {
                await UserDialogs.Instance.AlertAsync("Banner não está ligado a nenhuma loja!", "Erro", "Entendi");
                return;
            }
            EnderecoUtils.selecionarEndereco(async (endereco) => {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    var regraLoja = LojaFactory.create();
                    var loja = await regraLoja.pegar(peca.IdLoja);
                    await regraLoja.gravarAtual(loja);
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).atualizarMenu();
                    }

                    var promocaoPage = ProdutoUtils.gerarProdutoListaPromocao();
                    UserDialogs.Instance.HideLoading();
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(promocaoPage);
                    }
                    else
                    {
                        App.Current.MainPage = App.gerarRootPage(promocaoPage);
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            });
        }

        protected async void abrirProduto(BannerPecaInfo peca) {
            if (!peca.IdProduto.HasValue)
            {
                await UserDialogs.Instance.AlertAsync("Banner não está ligado a nenhum produto!", "Erro", "Entendi");
                return;
            }
            EnderecoUtils.selecionarEndereco(async (endereco) => {
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    var regraProduto = ProdutoFactory.create();
                    var regraLoja = LojaFactory.create();
                    var produto = await regraProduto.pegar(peca.IdProduto.Value);

                    var loja = await regraLoja.pegar(produto.IdLoja);
                    await regraLoja.gravarAtual(loja);
                    if (App.Current.MainPage is RootPage) {
                        ((RootPage)App.Current.MainPage).atualizarMenu();
                    }

                    var produtoPage = new ProdutoPage()
                    {
                        Title = produto.Nome,
                        Produto = produto
                    };
                    UserDialogs.Instance.HideLoading();
                    if (App.Current.MainPage is RootPage)
                    {
                        ((RootPage)App.Current.MainPage).PushAsync(produtoPage);
                    }
                    else
                    {
                        App.Current.MainPage = App.gerarRootPage(produtoPage);
                    }
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            });
        }

        protected async void OnClicked(object sender, EventArgs e) {
            var peca = this.Banner;
            if (peca == null) {
                UserDialogs.Instance.HideLoading();
                await UserDialogs.Instance.AlertAsync("Banner não encontrado!", "Erro", "Entendi");
                return;
            }
            switch (peca.Destino) {
                case BannerDestinoEnum.Url:
                    abrirUrl(peca);
                    break;
                case BannerDestinoEnum.Loja:
                    abrirLoja(peca);
                    break;
                default:
                    abrirProduto(peca);
                    break;
            }
        }
    }
}
