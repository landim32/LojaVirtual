using Acr.UserDialogs;
using Emagine.Banner.Controls;
using Emagine.Banner.Model;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Mapa.Model;
using Emagine.Produto.Cells;
using Emagine.Produto.Events;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class LojaListaPage : ContentPage
    {
        private bool _carregado = false;
        public event LojaListaEventHandler AoCarregar;

        private StackLayout _mainLayout;
        private BannerView _bannerView;
        private ListView _lojaListView;
        private Label _empresaLabel;

        private IList<BannerPecaInfo> _banners;
        private IList<LojaInfo> _lojas;

        public IList<BannerPecaInfo> Banners {
            get {
                return _banners;
            }
            set {
                _banners = value;
                _bannerView.ItemsSource = null;
                _bannerView.ItemsSource = _banners;
            }
        }

        public IList<LojaInfo> Lojas {
            get {
                return _lojas;
            }
            set {
                _lojas = value;
                _lojaListView.ItemsSource = null;
                _lojaListView.ItemsSource = _lojas;
            }
        }

        public LojaListaPage()
        {
            Title = "Selecione a Loja";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            inicializarComponente();

            _mainLayout = new StackLayout
            {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Spacing = 0,
                Children = {
                    _bannerView,
                    _lojaListView,
                    _empresaLabel
                }
            };
            Content = _mainLayout;
        }

        [Obsolete]
        public LocalInfo Local { get; set; } = null;

        public bool BannerVisivel {
            get {
                return _mainLayout.Children.Contains(_bannerView);
            }
            set {
                if (value) {
                    if (!_mainLayout.Children.Contains(_bannerView)) {
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

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (AoCarregar != null)
            {
                if (_carregado) return;
                UserDialogs.Instance.ShowLoading("Carregando...");
                try
                {
                    if (App.Current.MainPage is RootPage) {
                        _empresaLabel.Text = ((RootPage)App.Current.MainPage).NomeApp + " ®";
                    }
                    var regraLoja = LojaFactory.create();
                    var args = new LojaListaEventArgs();
                    await AoCarregar?.Invoke(this, args);
                    if (args.Banners != null)
                    {
                        //_bannerView.ItemsSource = args.Banners;
                        this.Banners = args.Banners;
                    }
                    if (args.Lojas != null)
                    {
                        //_lojaListView.ItemsSource = args.Lojas;
                        this.Lojas = args.Lojas;
                    }
                    _carregado = true;
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    //UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
                    await DisplayAlert("Erro", erro.Message, "Entendi");
                }
            }
            _bannerView.inicializarRotacao();
        }

        protected override void OnDisappearing()
        {
            _bannerView.finalizarRotacao();
            base.OnDisappearing();
        }

        private void inicializarComponente() {
            _bannerView = new BannerView();
            _lojaListView = new ListView {
                Style = Estilo.Current[Estilo.LISTA_PADRAO],
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                //SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(LojaFotoCell))
            };
            _lojaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _lojaListView.ItemTapped += async (sender, e) => {
                if (e == null)
                    return;
                var loja = (LojaInfo)((ListView)sender).SelectedItem;
                _lojaListView.SelectedItem = null;

                var regraLoja = LojaFactory.create();
                await regraLoja.gravarAtual(loja);
                if (App.Current.MainPage is RootPage) {
                    ((RootPage)App.Current.MainPage).atualizarMenu();
                }

                //var promocaoListaPage = ProdutoUtils.gerarProdutoListaDestaque();
                var promocaoListaPage = ProdutoUtils.gerarProdutoListaPromocao();
                if (App.Current.MainPage is RootPage)
                {
                    ((RootPage)App.Current.MainPage).PaginaAtual = promocaoListaPage;
                }
                else {
                    await Navigation.PushAsync(promocaoListaPage);
                }
            };
            _empresaLabel = new Label
            {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                Margin = new Thickness(0, 5),
                //Text = "Smart Tecnologia ®"
            };
        }
    }
}