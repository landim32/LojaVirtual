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
        //private List<TipoVeiculoInfo> _Veiculos;

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
            UserDialogs.Instance.ShowLoading("Enviando...");
            try
            {
                var regraTipoVeiculo = TipoVeiculoFactory.create();
                _VeiculoLst.ItemsSource = await regraTipoVeiculo.listar();
                UserDialogs.Instance.HideLoading();
            }
            catch (Exception erro)
            {
                UserDialogs.Instance.HideLoading();
                UserDialogs.Instance.ShowError(erro.Message, 8000);
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
            /*
            _Veiculos = new List<TipoCaminhaoInfo>()
            {
                new TipoCaminhaoInfo(){ Descricao = "VLC (até 3.9t)", ImgStr = "Veiculo_01.png", Codigo = 1 },
                new TipoCaminhaoInfo(){ Descricao = "Três Quartos (até 4t)", ImgStr = "Veiculo_02.png", Codigo = 2 },
                new TipoCaminhaoInfo(){ Descricao = "Toco (até 8t)", ImgStr = "Veiculo_03.png", Codigo = 3 },
                new TipoCaminhaoInfo(){ Descricao = "Truck (até 15t)", ImgStr = "Veiculo_04.png", Codigo = 4 },
                new TipoCaminhaoInfo(){ Descricao = "Bitruck (até 22t)", ImgStr = "Veiculo_05.png", Codigo = 5 },
                new TipoCaminhaoInfo(){ Descricao = "Carreta (até 25t)", ImgStr = "Veiculo_06.png", Codigo = 6 },
                new TipoCaminhaoInfo(){ Descricao = "Carreta LS (até 27t)", ImgStr = "Veiculo_07.png", Codigo = 7 },
                new TipoCaminhaoInfo(){ Descricao = "Bitrem (até 35t)", ImgStr = "Veiculo_08.png", Codigo = 8 },
                new TipoCaminhaoInfo(){ Descricao = "Rodo Trem (até 40t)", ImgStr = "Veiculo_09.png", Codigo = 9 }
            };
            _VeiculoLst.ItemsSource = _Veiculos;
            */
            _VeiculoLst.ItemTapped += (sender, e) =>
            {
                if (e == null)
                    return;
                var item = (TipoVeiculoInfo)((ListView)sender).SelectedItem;
                if(this.AoSelecionar != null){
                    this.AoSelecionar(this, item);
                }
                Navigation.PopAsync();
            };

        }
    }
}

