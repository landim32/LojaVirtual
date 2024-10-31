using Acr.UserDialogs;
using Emagine.Base.Estilo;
using Emagine.Base.Pages;
using Emagine.Mapa.Model;
using Emagine.Produto.Cells;
using Emagine.Produto.Factory;
using Emagine.Produto.Model;
using Emagine.Produto.Utils;
using FormsPlugin.Iconize;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Xamarin.Forms;

namespace Emagine.Produto.Pages
{
    public class LojaListaPage : ContentPage
    {
        private ListView _lojaListView;

        public LojaListaPage()
        {
            /*
            ToolbarItems.Add(new IconToolbarItem
            {
                Text = "Buscar",
                Icon = "fa-search",
                IconColor = Color.White,
                Order = ToolbarItemOrder.Primary,
                Command = new Command(() => {
                    //Navigation.PushAsync(new ProdutoListaPage());
                })
            });
            */

            Title = "Selecione a Loja";
            Style = Estilo.Current[Estilo.TELA_PADRAO];
            //BackgroundColor = Color.FromHex("#d9d9d9");
            inicializarComponente();
            Content = new StackLayout
            {
                Margin = new Thickness(3, 3),
                VerticalOptions = LayoutOptions.Fill,
                HorizontalOptions = LayoutOptions.Fill,
                Children = {
                    _lojaListView
                }
            };
        }

        public LocalInfo Local { get; set; }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            UserDialogs.Instance.ShowLoading("Carregando...");
            try
            {
                if (Local == null) {
                    throw new Exception("Nenhuma posição informada.");
                }
                var regraLoja = LojaFactory.create();
                _lojaListView.ItemsSource = await regraLoja.buscar(Local.Latitude, Local.Longitude);
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.Alert(erro.Message, "Erro", "Fechar");
            }
        }

        private void inicializarComponente() {
            _lojaListView = new ListView {
                Style = Estilo.Current[Estilo.LISTA_PADRAO],
                HasUnevenRows = true,
                RowHeight = -1,
                SeparatorVisibility = SeparatorVisibility.Default,
                //SeparatorColor = Estilo.Current.PrimaryColor,
                ItemTemplate = new DataTemplate(typeof(LojaFotoCell))
            };
            _lojaListView.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _lojaListView.ItemTapped += (sender, e) => {
                if (e == null)
                    return;
                var loja = (LojaInfo)((ListView)sender).SelectedItem;
                _lojaListView.SelectedItem = null;

                var regraLoja = LojaFactory.create();
                regraLoja.gravarAtual(loja);

                var promocaoListaPage = ProdutoUtils.gerarProdutoListaDestaque();
                ((RootPage)App.Current.MainPage).PaginaAtual = promocaoListaPage;
                //Navigation.PushAsync(promocaoListaPage);
            };
        }
    }
}