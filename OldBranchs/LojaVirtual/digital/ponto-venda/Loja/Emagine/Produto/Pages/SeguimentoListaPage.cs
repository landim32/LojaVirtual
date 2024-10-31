using Acr.UserDialogs;
using Emagine.Banner.Controls;
using Emagine.Banner.Model;
using Emagine.Base.Estilo;
using Emagine.Produto.Controls;
using Emagine.Produto.Model;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text;
using Xamarin.Forms;
using System.Linq;
using Emagine.Produto.Factory;
using Emagine.Base.Pages;
using Emagine.Endereco.Utils;

namespace Emagine.Produto.Pages
{
    public class SeguimentoListaPage: ContentPage
    {
        private BannerView _bannerView;
        private Grid _seguimentoGrid;
        private Label _empresaLabel;

        private IList<SeguimentoInfo> _seguimentos;
        private IList<BannerPecaInfo> _banners;

        public event EventHandler<int> AoBuscarPorRaio;
        public event EventHandler<SeguimentoInfo> AoClicar;

        public IList<SeguimentoInfo> Seguimentos {
            get {
                return _seguimentos;
            }
            set {
                _seguimentos = value;
                if (_seguimentos != null)
                {
                    atualizarSeguimento(_seguimentos);
                }
                else {
                    _seguimentoGrid.Children.Clear();
                }
            }
        }

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

        public SeguimentoListaPage() {
            inicializarComponente();
            Content = new StackLayout {
                Orientation = StackOrientation.Vertical,
                VerticalOptions = LayoutOptions.FillAndExpand,
                HorizontalOptions = LayoutOptions.FillAndExpand,
                Spacing = 0,
                Children = {
                    _bannerView,
                    new ScrollView {
                        Orientation = ScrollOrientation.Vertical,
                        HorizontalOptions = LayoutOptions.FillAndExpand,
                        VerticalOptions = LayoutOptions.FillAndExpand,
                        Content = _seguimentoGrid
                    },
                    _empresaLabel
                }
            };
            /*
            Content = new ScrollView
            {
                Orientation = ScrollOrientation.Vertical,
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Content = _seguimentoGrid
            };
            */
        }

        private void inicializarComponente()
        {
            _bannerView = new BannerView {
                VerticalOptions = LayoutOptions.Start,
                HorizontalOptions = LayoutOptions.Fill,
                HeightRequest = 160
            };

            _seguimentoGrid = new Grid {
                HorizontalOptions = LayoutOptions.FillAndExpand,
                VerticalOptions = LayoutOptions.FillAndExpand,
                Margin = 5,
                RowSpacing = 10,
                ColumnSpacing = 10
            };

            /*
            _seguimentoGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _seguimentoGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            _seguimentoGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            */
            _seguimentoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _seguimentoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            _seguimentoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });
            //_seguimentoGrid.ColumnDefinitions.Add(new ColumnDefinition { Width = new GridLength(1, GridUnitType.Star) });


            _empresaLabel = new Label {
                HorizontalOptions = LayoutOptions.Fill,
                VerticalOptions = LayoutOptions.Start,
                HorizontalTextAlignment = TextAlignment.Center,
                Margin = new Thickness(0, 5),
                Text = "Smart Tecnologia ®"
            };
        }

        private void atualizarSeguimento(IList<SeguimentoInfo> seguimentos) {
            _seguimentoGrid.Children.Clear();
            //_seguimentoGrid.RowDefinitions.Add(new RowDefinition { Height = new GridLength(1, GridUnitType.Star) });
            int left = 0, top = 0;
            foreach (var seguimento in seguimentos) {
                atualizarSeguimento(seguimento, left, top);
                left++;
                //if (left > 3) {
                if (left > 2) {
                    left = 0;
                    top++;
                }
            }
        }

        private void atualizarSeguimento(SeguimentoInfo seguimento, int left, int top) {
            var seguimentoView = new SeguimentoView {
                Seguimento = seguimento
            };
            seguimentoView.AoClicar += (sender, e) =>
            {
                EnderecoUtils.selecionarEndereco((endereco) => {
                    AoClicar?.Invoke(sender, e);
                });
            };
            _seguimentoGrid.Children.Add(seguimentoView, left, top);
        }

        protected override void OnAppearing()
        {
            base.OnAppearing();
            /*
            var regraLoja = LojaFactory.create();
            var raioBusca = regraLoja.RaioBusca;
            if (raioBusca <= 0) {
                raioBusca = 100;
            }
            */
            _bannerView.inicializarRotacao();
        }

        protected override void OnDisappearing()
        {
            _bannerView.finalizarRotacao();
            base.OnDisappearing();
        }

        protected override void OnBindingContextChanged()
        {
            base.OnBindingContextChanged();

        }
    }
}
