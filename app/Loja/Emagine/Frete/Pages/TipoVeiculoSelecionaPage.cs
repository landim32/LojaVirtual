using System;
using System.Collections.Generic;
using Acr.UserDialogs;
using Emagine.Frete.Cells;
using Emagine.Frete.Factory;
using Emagine.Frete.Model;
using Xamarin.Forms;

namespace Emagine.Frete.Pages
{
    public class TipoVeiculoSelecionaPage : ContentPage
    {
        private ListView _VeiculoLst;

        private IList<TipoVeiculoInfo> _veiculos;

        public IList<TipoVeiculoInfo> Veiculos {
            get {
                return _veiculos;
            }
            set {
                _veiculos = value;
                _VeiculoLst.ItemsSource = null;
                _VeiculoLst.ItemsSource = _veiculos;
            }
        }
        public event EventHandler<TipoVeiculoInfo> AoSelecionar;


        public TipoVeiculoSelecionaPage()
        {
            Title = "Selecione o tipo do veículo";
            inicializarComponente();
            Content = _VeiculoLst;
        }

        protected override async void OnAppearing()
        {
            base.OnAppearing();
            if (_veiculos == null)
            {
                UserDialogs.Instance.ShowLoading("Enviando...");
                try
                {
                    var regraTipoVeiculo = TipoVeiculoFactory.create();
                    _veiculos = await regraTipoVeiculo.listar();
                    _VeiculoLst.ItemsSource = null;
                    _VeiculoLst.ItemsSource = _veiculos;
                    UserDialogs.Instance.HideLoading();
                }
                catch (Exception erro)
                {
                    UserDialogs.Instance.HideLoading();
                    await UserDialogs.Instance.AlertAsync(erro.Message, "Erro", "Entendi");
                }
            }
        }

        private void inicializarComponente()
        {
            _VeiculoLst = new ListView {
                HasUnevenRows = true,
                RowHeight = -1,
                ItemTemplate = new DataTemplate(typeof(TipoVeiculoCell)),
                SeparatorVisibility = SeparatorVisibility.Default
            };
            _VeiculoLst.SetBinding(ListView.ItemsSourceProperty, new Binding("."));
            _VeiculoLst.ItemTapped += (sender, e) =>
            {
                if (e == null)
                    return;
                var item = (TipoVeiculoInfo)((ListView)sender).SelectedItem;
                AoSelecionar?.Invoke(this, item);
                //Navigation.PopAsync();
            };

        }
    }
}

