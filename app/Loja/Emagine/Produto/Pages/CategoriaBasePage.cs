using Acr.UserDialogs;
using Emagine.Banner.Controls;
using Emagine.Banner.Factory;
using Emagine.Banner.Model;
using Emagine.Banner.Utils;
using Emagine.Base.Estilo;
using Emagine.Produto.Cells;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Pages;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public abstract class CategoriaBasePage : ContentPage
    {
        protected StackLayout _mainLayout;
        protected BannerView _bannerView;
        protected SearchBar _buscaBar;
        protected Label _empresaLabel;


        public int? IdCategoria { get; set; }
        public event EventHandler<ProdutoBasePage> AoAbrirProdutoLista;

        public CategoriaBasePage()
        {
            Style = Estilo.Current[Estilo.TELA_EM_BRANCO];
            inicializarComponente();
        }

        public bool BannerVisivel
        {
            get
            {
                return _mainLayout.Children.Contains(_bannerView);
            }
            set
            {
                if (value)
                {
                    if (!_mainLayout.Children.Contains(_bannerView))
                    {
                        _mainLayout.Children.Insert(0, _bannerView);
                    }
                }
                else
                {
                    if (_mainLayout.Children.Contains(_bannerView))
                    {
                        _mainLayout.Children.Remove(_bannerView);
                    }
                }
            }
        }

        protected abstract void executarAtualizarCategoria(IList<CategoriaInfo> itens);

        protected async Task atualizarCategoria() {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja != null)
            {
                _empresaLabel.Text = loja.Nome;
                var regraCategoria = CategoriaFactory.create();
                UserDialogs.Instance.ShowLoading("Carregando...");
                try {
                    if (BannerVisivel)
                    {
                        var regraBanner = BannerPecaFactory.create();
                        _bannerView.ItemsSource = await regraBanner.gerar(new BannerFiltroInfo
                        {
                            SlugBanner = BannerUtils.CATEGORIA,
                            IdLoja = loja.Id,
                            Ordem = BannerOrdemEnum.PorOrdem
                        });
                    }
                    if (IdCategoria.HasValue) {
                        executarAtualizarCategoria(await regraCategoria.listarPorCategoria(loja.Id, IdCategoria.Value));
                    }
                    else {
                        executarAtualizarCategoria(await regraCategoria.listarPai(loja.Id));
                    }
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    UserDialogs.Instance.Alert(erro.Message, "Erro", "Entendi");
                }
            }
            else
            {
                await DisplayAlert("Aviso", "Nenhuma loja selecionada.", "Fechar");
            }
        }

        protected async Task abrirCategoria(CategoriaInfo categoria) {
            var regraLoja = LojaFactory.create();
            var loja = regraLoja.pegarAtual();
            if (loja == null)
            {
                await DisplayAlert("Aviso", "Nenhuma loja selecionada.", "Fechar");
                return;
            }

            var regraCategoria = CategoriaFactory.create();
            var categoriasFilho = await regraCategoria.listarPorCategoria(loja.Id, categoria.Id);

            if (categoriasFilho.Count > 0) {
                var categoriaPage = CategoriaPageFactory.create();
                categoriaPage.BannerVisivel = this.BannerVisivel;
                categoriaPage.Title = categoria.Nome;
                categoriaPage.IdCategoria = categoria.Id;
                if (AoAbrirProdutoLista != null) {
                    categoriaPage.AoAbrirProdutoLista += AoAbrirProdutoLista;
                }
                await Navigation.PushAsync(categoriaPage);
            }
            else {
                var produtoPage = ProdutoUtils.gerarProdutoListaPorCategoria(categoria);
                if (AoAbrirProdutoLista != null) {
                    AoAbrirProdutoLista(this, produtoPage);
                }
                else {
                    await Navigation.PushAsync(produtoPage);
                }
            }
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            _bannerView.inicializarRotacao();
            await atualizarCategoria();
        }

        protected override void OnDisappearing()
        {
            _bannerView.finalizarRotacao();
            base.OnDisappearing();
        }

        protected virtual void inicializarComponente() {

            _bannerView = new BannerView();

            _buscaBar = new SearchBar {
                Placeholder = "Faça sua lista de compras",
                SearchCommand = new Command(() => {
                    var listaCompraPage = new ListaCompraPage {
                        Title = "Lista de Compras"
                    };
                    listaCompraPage.adicionarPalavraChave(_buscaBar.Text);
                    _buscaBar.Text = "";
                    Navigation.PushAsync(listaCompraPage);
                })
            };

            _empresaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                FontAttributes = FontAttributes.Bold,
                Margin = new Thickness(0, 0, 0, 3),
                Text = "Smart Tecnologia ®"
            };
        }
    }
}